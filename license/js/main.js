//delete selected row
function del_rec(pname,sbu)
{
var formobj=document.frm;
var count = 0;
for(var x=0; x<formobj.chkMuserid1.length; x++){
if(formobj.chkMuserid1[x].checked==true){
count++
}
} // end for loop
if(count==0){
alert("Please select atleast one checkbox.");
}
else if(confirm("Are you sure you want to delete record?") && count>0 )
{
formobj.action="index.php?page="+pname+"&Action=Delete&sbu="+sbu;
formobj.submit();
}
}
//select all
function selectAllChk()
{
var formObj=document.frm;
if(formObj.chkSelectAll.checked)
{
checked=true;
}
else
{
checked=false;
}
for (var i=0;i < formObj.length;i++) 
{
fldObj = formObj.elements[i];
if (fldObj.type == 'checkbox')
{
fldObj.checked = checked;
}
}
}
//end dilip