/* Copyright (C) 2007 - 2011 YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

function addOperator( select, target )
{
	selected = new Array();
	options= select.options;
	for(var i = 0; i < options.length; i++)
	{
		if(options[i].selected)
		{
			selected.push(options[i]);
		}
	}
	
	html = '<table border="0" width="100%">';
	for(var i = 0; i < selected.length; i++)
	{
		html = html + '<tr>';
		html = html + '<td><span style="font-weight:bold;">'+selected[i].innerHTML+' Operators: </span></td>';
		html = html + '<td><select multiple="multiple" name="params[elements_operators_'+selected[i].value+'][]">';
		html = html + '<option value="equal" selected="selected">=</option>';
		html = html + '<option value="less">&lt;</option>';
		html = html + '<option value="more">&gt;</option>';
		html = html + '<option value="contains">Contains</option>';
		html = html + '<option value="exact">Is Exactly</option>';
		html = html + '</select></td></tr>';
		
		
	}
	html = html + '</table>';
	
	$(target).innerHTML=html;
}