/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
*       
*       In case you've already got the developer version, then this is one of the few 
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/
if (!Bs_Objects) {var Bs_Objects = [];};function Bs_DataGrid() {
this._objectId;this._tagId;this._header;this._data;this._constructor = function() {
this._id = Bs_Objects.length;Bs_Objects[this._id] = this;this._objectId = "Bs_DateGrid_"+this._id;}
this.render = function() {
var out = new Array();var tdSettings = new Array();out[out.length] = '<table border="0" cellspacing="0" cellpadding="2" class="bsDg_table">';if (typeof(this._header) == 'object') {
out[out.length] = '<tr class="bsDb_tr_header">';for (var i=0; i<this._header.length; i++) {
tdSettings[i] = new Array();if (typeof(this._header[i]) == 'object') {
var text     = this._header[i]['text'];var hasProps = true;} else {
var text     = this._header[i];var hasProps = false;}
out[out.length] = '<td';out[out.length] = ' id="' + this._objectId + '_title_td_' + i + '"';out[out.length] = ' style="';if (this._header[i]['sort'] != false) {
out[out.length] = 'cursor:hand;cursor:pointer;';}
if (hasProps) {
if (!bs_isEmpty(this._header[i]['align'])) {
out[out.length] = 'text-align:' + this._header[i]['align'] + ';';tdSettings[i]['align'] = this._header[i]['align'];}
if (!bs_isEmpty(this._header[i]['width'])) {
out[out.length] = 'width:' + this._header[i]['width'] + ';';}
}
out[out.length] = '"';if (!bs_isEmpty(this._header[i]['nowrap']) && this._header[i]['nowrap']) {
out[out.length] = ' nowrap';}
if (this._header[i]['sort'] != false) {
out[out.length] = ' onclick="Bs_Objects['+this._id+'].orderByColumn(' + i + ');"';}
out[out.length] = ' class="bsDb_td_header"';out[out.length] = '>' + text + '</td>';}
out[out.length] = '</tr>';}
if (typeof(this._data) == 'object') {
for (var i=0; i<this._data.length; i++) {
out[out.length] = '<tr class="bsDg_tr_row_zebra_' + (i % 2) + '">';for (var j=0; j<this._data[i].length; j++) {
if (bs_isNull(this._data[i][j])) continue;if (typeof(this._data[i][j]) == 'object') {
var text = (typeof(this._data[i][j]['text']) != 'undefined') ? this._data[i][j]['text'] : '';} else if (typeof(this._data[i][j]) == 'undefined') {
this._data[i][j] = '';var text = this._data[i][j];} else {
var text = this._data[i][j];}
out[out.length] = '<td';if (typeof(this._data[i][j]['title']) != 'undefined') {
out[out.length] = ' title="' + this._data[i][j]['title'] + '"';}
out[out.length] = ' style="';if (!bs_isEmpty(tdSettings[j]['align'])) {
out[out.length] = 'text-align:' + tdSettings[j]['align'] + ';';}
out[out.length] = '"';if (typeof(this._data[i][j]['onclick']) != 'undefined') {
out[out.length] = ' onclick="' + this._data[i][j]['onclick'] + '"';}
var zebraRowTdClass = 'bsDg_td_row_zebra_' + (i % 2);out[out.length] = ' class="' + zebraRowTdClass + ' bsDg_row_' + i + ' bsDg_col_' + j + '"';out[out.length] = '>';out[out.length] = text;out[out.length] = '</td>';}
out[out.length] = '</tr>' + "\n";}
}
out[out.length] = '</table>';return out.join('');}
this.drawInto = function(tagId) {
this._tagId = tagId;document.getElementById(tagId).innerHTML = this.render();}
this.orderByColumn = function(column) {
bs_dg_globalColumn = column;if ((typeof(this._header[column]['sort']) != 'undefined') && (this._header[column]['sort'] == 'numeric')) {
bs_dg_sort = 'numeric';} else {
bs_dg_sort = 'alpha';}
this._data.sort(bs_datagrid_sort);this.drawInto(this._tagId);document.getElementById(this._objectId + '_title_td_' + column).className += ' bsDb_td_header_sort';}
this._constructor();}
var bs_dg_globalColumn;var bs_dg_sort;function bs_datagrid_sort(a,b) {
if (typeof(a[bs_dg_globalColumn]) == 'object') {
if (typeof(a[bs_dg_globalColumn]['order']) != 'undefined') {
valA = a[bs_dg_globalColumn]['order'];} else {
valA = a[bs_dg_globalColumn]['text'];}
} else {
valA = a[bs_dg_globalColumn];}
if (typeof(b[bs_dg_globalColumn]) == 'object') {
if (typeof(b[bs_dg_globalColumn]['order']) != 'undefined') {
valB = b[bs_dg_globalColumn]['order'];} else {
valB = b[bs_dg_globalColumn]['text'];}
} else {
valB = b[bs_dg_globalColumn];}
if (bs_dg_sort == 'numeric') {
valA = parseInt(valA);valB = parseInt(valB);if (valA < valB) {
return 1;} else if (valA > valB) {
return -1;} else {
return 0;}
} else {
if (valA > valB) {
return 1;} else if (valA < valB) {
return -1;} else {
return 0;}
}
}
