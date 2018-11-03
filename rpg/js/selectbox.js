/**
 * Empty Select Box
 * @param eid Element ID
 * @param value text
 * @param text text
 * @author Neeraj.Singh
 */

function emptySelectBoxById(eid, value, text) {
    document.getElementById(eid).innerHTML = "<option value='" + value + "'>" + text + "</option>";
}
/**
 * Reset Select Box
 * @param eid Element ID
 */

function resetSelectBoxById(eid) {
    document.getElementById(eid).options[0].selected = 'selected';
}
/**
 * Set Select Box Selection By Index
 * @param eid Element ID
 * @param eindx Element Index
 */

function setSelectBoxByIndex(eid, eindx) {
    document.getElementById(eid).getElementsByTagName('option')[eindx].selected = 'selected';
    //or
    document.getElementById(eid).options[eindx].selected = 'selected';
}
/**
 * Set Select Box Selection By Value
 * @param eid Element ID
 * @param eval Element Index
 */

function setSelectBoxByValue(eid, eval) {
    document.getElementById(eid).value = eval;
}
/**
 * Set Select Box Selection By Text
 * @param eid Element ID
 * @param eval Element Index
 */

function setSelectBoxByText(eid, etxt) {
    var eid = document.getElementById(eid);
    for (var i = 0; i < eid.options.length; ++i) {
        if (eid.options[i].text === etxt)
            eid.options[i].selected = true;
    }
}
/**
 * Get Select Box Text By ID
 * @param eid Element ID
 * @return string
 */

function getSelectBoxText(eid) {
    return document.getElementById(eid).options[document.getElementById(eid).selectedIndex].text;
}
/**
 * Get Select Box Value By ID
 * @param eid Element ID
 * @return string
 */

function getSelectBoxValue(id) {
    return document.getElementById(id).options[document.getElementById(id).selectedIndex].value;
}