function setTimeFormat(obj, event) {
    var charCode;

    if (event.keyCode > 0)
    {
        charCode = event.which || event.keyCode;
    }
    else if (typeof (event.charCode) != "undefined")
    {
        charCode = event.which || event.keyCode;
    }
	//allow colon or backspace or delete
    if (charCode == 58 || charCode == 8 || charCode == 46 )
    {
        return true
    }
	//set hours to 23 if higher
	if (obj.value.length == 2)
	{
		obj.value = parseInt(obj.value) >= 24 ? "23" : obj.value
		obj.value = obj.value + ":";
	}
	else if (obj.value.length == 4)
	{
		//set minutes to 59 if higher
		if (parseInt(obj.value.substr(3,1)) >= 6)
		{
			obj.value = obj.value.substr(0,3) + "59";
		}
	}
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    {
        return false;
    }
    return true;
}