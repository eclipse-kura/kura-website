/**
 * Copyright (c) 2018 Eurotech and/or its affiliates.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * SPDX-License-Identifier: EPL-2.0
 */

var DataTable = function (dataUrl, tableId, filtersId, columnsDescriptors) {
  this.data = {}
  this.items = []
  this.activeFilters = []
  this.columnsDescriptors = columnsDescriptors
  this.table = document.getElementById(tableId)
  this.filters = document.getElementById(filtersId)

  for (var i = 0; i < columnsDescriptors.length; i++) {
    this.items[i] = {}
  }

  var self = this

  var req = new XMLHttpRequest();
  req.onload = function () {
    self.load(JSON.parse(this.responseText).data)
    self.createFilters()
    self.render()
  };
  req.open('get', dataUrl, true);
  req.send();
}

DataTable.prototype.createFilter = function (label, index, values) {
  var div = document.createElement('div')
  div.className = "form-group"

  var l = document.createElement('label')
  l.innerText = label
  div.appendChild(l)

  var select = document.createElement('select')
  select.className = "custom-select custom-select-sm"

  var all = document.createElement('option')
  all.innerText = 'all';
  all.value = 'all'
  select.appendChild(all)

  for (var entry in values) {
    var option = document.createElement('option')
    option.innerText = entry
    option.value = entry
    select.appendChild(option)
  }

  var defaultValue = this.columnsDescriptors[index].default
  if (defaultValue) {
    select.value = defaultValue
    this.activeFilters[index] = defaultValue
  }

  var self = this

  select.addEventListener('change', function () {
    if (select.value === 'all') {
      delete self.activeFilters[index]
    } else {
      self.activeFilters[index] = select.value
    }
    self.render()
  })

  div.appendChild(select)

  return div
}

DataTable.prototype.createFilters = function () {
  for (var i=0;i<this.columnsDescriptors.length;i++) {
    if (this.columnsDescriptors[i].filter) {
      this.filters.appendChild(this.createFilter(this.columnsDescriptors[i].name, i, this.items[i]))
    }
  }
}

DataTable.prototype.indexRow = function (row) {
  for (var i=0; i<row.length; i++) {
    var data = row[i]
    this.items[i][data] = true
  }
}

DataTable.prototype.load = function (rows) {
  for (var i=0; i<rows.length; i++) {
    this.indexRow(rows[i])
  }
  this.data = rows
}

DataTable.prototype.renderHeader = function () {
  var head = document.createElement('tr')

  for (var i=0;i<this.columnsDescriptors.length; i++) {
    var h = document.createElement('th');
    h.className = 'text-center'
    h.innerText = this.columnsDescriptors[i].name
    head.appendChild(h)
  }

  this.table.appendChild(head)
}

DataTable.prototype.renderRow = function (row) {
  var r = document.createElement('tr')
  for (var i=0; i<row.length; i++) {
    var col = document.createElement('td')
    col.className = 'text-center'
    var val = row[i]
    if (this.columnsDescriptors[i].renderer) {
      col.appendChild(this.columnsDescriptors[i].renderer(val))
    } else {
      col.innerText = val
    }
    r.appendChild(col)
  }
  this.table.appendChild(r)
}

DataTable.prototype.matchesFilter = function (row) {
  for (var i=0; i<row.length; i++) {
    if (this.activeFilters[i] === undefined) {
      continue
    }
    if (row[i] !== this.activeFilters[i]) {
      return false
    }
  }
  return true
}

DataTable.prototype.render = function () {
  while (this.table.firstChild) {
    this.table.removeChild(this.table.firstChild)
  }
  this.renderHeader()
  for (var i=0; i<this.data.length; i++) {
    var row = this.data[i]
    if (!this.matchesFilter(row)) {
      continue
    }
    this.renderRow(row)
  }
}
