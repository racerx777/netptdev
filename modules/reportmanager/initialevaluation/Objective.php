<script language="javascript" type="text/javascript">
function getLastTestNumber() {
	var nn=document.getElementById('lasttestnumber');
	return(nn.value);
}

function setLastTestNumber(val) {
	var nn=document.getElementById('lasttestnumber')
	if(val)
		nn.value=nn.value+1.0;
	else
		nn.value++;
	return(nn.value);
}

// Table Functions
function TableRowAdd112(element) {
	try {
		var elementdiv=element.parentNode; // adddelbutton
		var row=elementdiv.parentNode; // tr rowadd
		var tbody=row.parentNode; // tbody no id
		var table=tbody.parentNode; // table
		var form=table.parentNode;
		var bodypartdiv1=form.parentNode;
		var bodypartdiv2=bodypartdiv1.parentNode;
		var bodypart=bodypartdiv2.parentNode;
		var tab=bodypart.parentNode;

// Test value entered
		if(row.children[0].children[0].value!='') {
			var sectionrowslength=tbody.rows.length
			oldaddrow=tbody.rows[sectionrowslength-1];
			newaddrow=row.cloneNode(true);
			n=setLastTestNumber();
			var idprefix=table.id+'_'+n;
			var nameprefix='report[detail_bodypart_test]['+n+']';

// Where is the current bodypart id from this element?
			var bilateral = document.getElementById('bilateral'+bodypart.id);
// Is this Comparitive
			var compreportdate = document.getElementById('rhcompreportdate');

			oldaddrow.cells[0].children[0].id=idprefix+'_rdbtrtname'; 
			oldaddrow.cells[0].children[0].name=nameprefix+'[rdbtrtname]'; 
			oldaddrow.cells[1].children[0].id=idprefix+'_result1_m1_right'; 
			oldaddrow.cells[1].children[0].name=nameprefix+'[rdbtresult1][m1][right]'; 

			if(bilateral.value=='1') {
				oldaddrow.cells[2].children[0].id=idprefix+'_result1_m1_left'; 
				oldaddrow.cells[2].children[0].name=nameprefix+'[rdbtresult1][m1][left]'; 
				if(compreportdate.value!='') {
					oldaddrow.cells[3].children[0].id=idprefix+'_result2_m1_right'; 
					oldaddrow.cells[3].children[0].name=nameprefix+'[rdbtresult2][m1][right]'; 
					oldaddrow.cells[4].children[0].id=idprefix+'_result2_m1_left'; 
					oldaddrow.cells[4].children[0].name=nameprefix+'[rdbtresult2][m1][left]'; 
					InjuryTableRowFormat(oldaddrow.cells[5]);
					oldaddrow.cells[5].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[5].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[5].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[5].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
				else {
					InjuryTableRowFormat(oldaddrow.cells[3]);
					oldaddrow.cells[3].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[3].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[3].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[3].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
			}
			else {
				if(compreportdate.value!='') {
					oldaddrow.cells[2].children[0].id=idprefix+'_result2_m1_right'; 
					oldaddrow.cells[2].children[0].name=nameprefix+'[rdbtresult2][m1][right]'; 
					InjuryTableRowFormat(oldaddrow.cells[3]);
					oldaddrow.cells[3].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[3].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[3].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[3].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
				else {
					InjuryTableRowFormat(oldaddrow.cells[2]);
					oldaddrow.cells[2].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[2].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[2].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[2].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
			}
			oldaddrow.id=idprefix;
			eventcount=oldaddrow.cells.length-1;
			for(i=0; i<eventcount; i++) {
				if( document.attachEvent ) {
					newaddrow.children[i].children[0].attachEvent( 'onkeyup', cAutocomplete.complete )
				}
				else if( document.addEventListener ) {
					newaddrow.children[i].children[0].addEventListener( 'keyup', cAutocomplete.complete, false )
				}
				newaddrow.children[i].children[0].value='';
			}
			newaddrow.children[i].children[0].value=oldaddrow.cells[i].children[0].value; 
			newaddrow.children[i].children[1].value=oldaddrow.cells[i].children[1].value; 
			tbody.appendChild(newaddrow);
		}

	}
	catch(e) {
		alert(e);
	}
}

function TableRowAdd142(element) {
	try {
		var elementdiv=element.parentNode; // adddelbutton
		var row=elementdiv.parentNode; // tr rowadd
		var tbody=row.parentNode; // tbody no id
		var table=tbody.parentNode; // table
		var form=table.parentNode;
		var bodypartdiv1=form.parentNode;
		var bodypartdiv2=bodypartdiv1.parentNode;
		var bodypart=bodypartdiv2.parentNode;
		var tab=bodypart.parentNode;

// Test value entered
		if(row.children[0].children[0].value!='') {
			var sectionrowslength=tbody.rows.length
			oldaddrow=tbody.rows[sectionrowslength-1];
			newaddrow=row.cloneNode(true);
			n=setLastTestNumber();
			var idprefix=table.id+'_'+n;
			var nameprefix='report[detail_bodypart_test]['+n+']';

// Where is the current bodypart id from this element?
			var bilateral = document.getElementById('bilateral'+bodypart.id);
// Is this Comparitive
			var compreportdate = document.getElementById('rhcompreportdate');

			oldaddrow.cells[0].children[0].id=idprefix+'_rdbtrtname'; 
			oldaddrow.cells[0].children[0].name=nameprefix+'[rdbtrtname]'; 

			oldaddrow.cells[1].children[0].id=idprefix+'_result1_m1_right'; 
			oldaddrow.cells[1].children[0].name=nameprefix+'[rdbtresult1][m1][right]'; 
			oldaddrow.cells[2].children[0].id=idprefix+'_result1_m2_right'; 
			oldaddrow.cells[2].children[0].name=nameprefix+'[rdbtresult1][m2][right]'; 
			oldaddrow.cells[3].children[0].id=idprefix+'_result1_m3_right'; 
			oldaddrow.cells[3].children[0].name=nameprefix+'[rdbtresult1][m3][right]'; 
			oldaddrow.cells[4].children[0].id=idprefix+'_result1_m4_right'; 
			oldaddrow.cells[4].children[0].name=nameprefix+'[rdbtresult1][m4][right]'; 

			if(bilateral.value=='1') {
				oldaddrow.cells[5].children[0].id=idprefix+'_result1_m1_left'; 
				oldaddrow.cells[5].children[0].name=nameprefix+'[rdbtresult1][m1][left]'; 
				oldaddrow.cells[6].children[0].id=idprefix+'_result1_m2_left'; 
				oldaddrow.cells[6].children[0].name=nameprefix+'[rdbtresult1][m2][left]'; 
				oldaddrow.cells[7].children[0].id=idprefix+'_result1_m3_left'; 
				oldaddrow.cells[7].children[0].name=nameprefix+'[rdbtresult1][m3][left]'; 
				oldaddrow.cells[8].children[0].id=idprefix+'_result1_m4_left'; 
				oldaddrow.cells[8].children[0].name=nameprefix+'[rdbtresult1][m4][left]'; 

				if(compreportdate.value!='') {
					oldaddrow.cells[9].children[0].id=idprefix+'_result2_m1_right'; 
					oldaddrow.cells[9].children[0].name=nameprefix+'[rdbtresult2][m1][right]'; 
					oldaddrow.cells[10].children[0].id=idprefix+'_result2_m2_right'; 
					oldaddrow.cells[10].children[0].name=nameprefix+'[rdbtresult2][m2][right]'; 
					oldaddrow.cells[11].children[0].id=idprefix+'_result2_m3_right'; 
					oldaddrow.cells[11].children[0].name=nameprefix+'[rdbtresult2][m3][right]'; 
					oldaddrow.cells[12].children[0].id=idprefix+'_result2_m4_right'; 
					oldaddrow.cells[12].children[0].name=nameprefix+'[rdbtresult2][m4][right]'; 
					oldaddrow.cells[13].children[0].id=idprefix+'_result2_m1_left'; 
					oldaddrow.cells[13].children[0].name=nameprefix+'[rdbtresult2][m1][left]'; 
					oldaddrow.cells[14].children[0].id=idprefix+'_result2_m2_left'; 
					oldaddrow.cells[14].children[0].name=nameprefix+'[rdbtresult2][m2][left]'; 
					oldaddrow.cells[15].children[0].id=idprefix+'_result2_m3_left'; 
					oldaddrow.cells[15].children[0].name=nameprefix+'[rdbtresult2][m3][left]'; 
					oldaddrow.cells[16].children[0].id=idprefix+'_result2_m4_left'; 
					oldaddrow.cells[16].children[0].name=nameprefix+'[rdbtresult2][m4][left]'; 

					InjuryTableRowFormat(oldaddrow.cells[17]);
					oldaddrow.cells[17].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[17].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[17].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[17].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
				else {
					InjuryTableRowFormat(oldaddrow.cells[9]);
					oldaddrow.cells[9].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[9].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[9].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[9].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
			}
			else {
				if(compreportdate.value!='') {
					oldaddrow.cells[5].children[0].id=idprefix+'_result2_m1_right'; 
					oldaddrow.cells[5].children[0].name=nameprefix+'[rdbtresult2][m1][right]'; 
					oldaddrow.cells[6].children[0].id=idprefix+'_result2_m2_right'; 
					oldaddrow.cells[6].children[0].name=nameprefix+'[rdbtresult2][m2][right]'; 
					oldaddrow.cells[7].children[0].id=idprefix+'_result2_m3_right'; 
					oldaddrow.cells[7].children[0].name=nameprefix+'[rdbtresult2][m3][right]'; 
					oldaddrow.cells[8].children[0].id=idprefix+'_result2_m4_right'; 
					oldaddrow.cells[8].children[0].name=nameprefix+'[rdbtresult2][m4][right]'; 
					InjuryTableRowFormat(oldaddrow.cells[9]);
					oldaddrow.cells[9].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[9].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[9].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[9].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
				else {
					InjuryTableRowFormat(oldaddrow.cells[5]);
					oldaddrow.cells[5].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[5].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[5].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[5].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
			}
			oldaddrow.id=idprefix;
			eventcount=oldaddrow.cells.length-1;
			for(i=0; i<eventcount; i++) {
				if( document.attachEvent ) {
					newaddrow.children[i].children[0].attachEvent( 'onkeyup', cAutocomplete.complete )
				}
				else if( document.addEventListener ) {
					newaddrow.children[i].children[0].addEventListener( 'keyup', cAutocomplete.complete, false )
				}
				newaddrow.children[i].children[0].value='';
			}
			newaddrow.children[i].children[0].value=oldaddrow.cells[i].children[0].value; 
			newaddrow.children[i].children[1].value=oldaddrow.cells[i].children[1].value; 
			tbody.appendChild(newaddrow);
		}

	}
	catch(e) {
		alert(e);
	}
}

function TableRowAdd222(element) {
	try {
		var elementdiv=element.parentNode; // adddelbutton
		var row=elementdiv.parentNode; // tr rowadd
		var tbody=row.parentNode; // tbody no id
		var table=tbody.parentNode; // table
		var form=table.parentNode;
		var bodypartdiv1=form.parentNode;
		var bodypartdiv2=bodypartdiv1.parentNode;
		var bodypart=bodypartdiv2.parentNode;
		var tab=bodypart.parentNode;

// Test value entered
		if(row.children[0].children[0].value!='') {
			var sectionrowslength=tbody.rows.length
			oldaddrow=tbody.rows[sectionrowslength-1];
			newaddrow=row.cloneNode(true);
			n=setLastTestNumber();
			var idprefix=table.id+'_'+n;
			var nameprefix='report[detail_bodypart_test]['+n+']';

// Where is the current bodypart id from this element?
			var bilateral = document.getElementById('bilateral'+bodypart.id);
// Is this Comparitive
			var compreportdate = document.getElementById('rhcompreportdate');

			oldaddrow.cells[0].children[0].id=idprefix+'_rdbtrtname'; 
			oldaddrow.cells[0].children[0].name=nameprefix+'[rdbtrtname]'; 
			oldaddrow.cells[1].children[0].id=idprefix+'_result1_m1_right'; 
			oldaddrow.cells[1].children[0].name=nameprefix+'[rdbtresult1][m1][right]'; 
			oldaddrow.cells[2].children[0].id=idprefix+'_result1_m2_right'; 
			oldaddrow.cells[2].children[0].name=nameprefix+'[rdbtresult1][m2][right]'; 

			if(bilateral.value=='1') {
				oldaddrow.cells[3].children[0].id=idprefix+'_result1_m1_left'; 
				oldaddrow.cells[3].children[0].name=nameprefix+'[rdbtresult1][m1][left]'; 
				oldaddrow.cells[4].children[0].id=idprefix+'_result1_m2_left'; 
				oldaddrow.cells[4].children[0].name=nameprefix+'[rdbtresult1][m2][left]'; 
				if(compreportdate.value!='') {
					oldaddrow.cells[5].children[0].id=idprefix+'_result2_m1_right'; 
					oldaddrow.cells[5].children[0].name=nameprefix+'[rdbtresult2][m1][right]'; 
					oldaddrow.cells[6].children[0].id=idprefix+'_result2_m2_right'; 
					oldaddrow.cells[6].children[0].name=nameprefix+'[rdbtresult2][m2][right]'; 
					oldaddrow.cells[7].children[0].id=idprefix+'_result2_m1_left'; 
					oldaddrow.cells[7].children[0].name=nameprefix+'[rdbtresult2][m1][left]'; 
					oldaddrow.cells[8].children[0].id=idprefix+'_result2_m2_left'; 
					oldaddrow.cells[8].children[0].name=nameprefix+'[rdbtresult2][m2][left]'; 
					oldaddrow.cells[9].children[0].id=idprefix+'_rdbtrtmname'; 
					oldaddrow.cells[9].children[0].name=nameprefix+'[rdbtrtmname]'; 
					InjuryTableRowFormat(oldaddrow.cells[10]);
					oldaddrow.cells[10].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[10].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[10].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[10].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
				else {
					oldaddrow.cells[5].children[0].id=idprefix+'_rdbtrtmname'; 
					oldaddrow.cells[5].children[0].name=nameprefix+'[rdbtrtmname]'; 
					InjuryTableRowFormat(oldaddrow.cells[6]);
					oldaddrow.cells[6].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[6].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[6].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[6].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
			}
			else {
				if(compreportdate.value!='') {
					oldaddrow.cells[3].children[0].id=idprefix+'_result2_m1_left'; 
					oldaddrow.cells[3].children[0].name=nameprefix+'[rdbtresult2][m1][left]'; 
					oldaddrow.cells[4].children[0].id=idprefix+'_result2_m2_left'; 
					oldaddrow.cells[4].children[0].name=nameprefix+'[rdbtresult2][m2][left]'; 
					oldaddrow.cells[5].children[0].id=idprefix+'_rdbtrtmname'; 
					oldaddrow.cells[5].children[0].name=nameprefix+'[rdbtrtmname]'; 
					InjuryTableRowFormat(oldaddrow.cells[6]);
					oldaddrow.cells[6].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[6].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[6].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[6].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
				else {
					oldaddrow.cells[3].children[0].id=idprefix+'_rdbtrtmname'; 
					oldaddrow.cells[3].children[0].name=nameprefix+'[rdbtrtmname]'; 
					InjuryTableRowFormat(oldaddrow.cells[4]);
					oldaddrow.cells[4].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[4].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[4].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[4].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
			}
			oldaddrow.id=idprefix;
			eventcount=oldaddrow.cells.length-1;
			for(i=0; i<eventcount; i++) {
				if( document.attachEvent ) {
					newaddrow.children[i].children[0].attachEvent( 'onkeyup', cAutocomplete.complete )
				}
				else if( document.addEventListener ) {
					newaddrow.children[i].children[0].addEventListener( 'keyup', cAutocomplete.complete, false )
				}
				newaddrow.children[i].children[0].value='';
			}
			newaddrow.children[i].children[0].value=oldaddrow.cells[i].children[0].value; 
			newaddrow.children[i].children[1].value=oldaddrow.cells[i].children[1].value; 
			tbody.appendChild(newaddrow);
		}

	}
	catch(e) {
		alert(e);
	}
}

function TableRowAdd212(element) {
	try {
		var elementdiv=element.parentNode; // adddelbutton
		var row=elementdiv.parentNode; // tr rowadd
		var tbody=row.parentNode; // tbody no id
		var table=tbody.parentNode; // table
		var form=table.parentNode;
		var bodypartdiv1=form.parentNode;
		var bodypartdiv2=bodypartdiv1.parentNode;
		var bodypart=bodypartdiv2.parentNode;
		var tab=bodypart.parentNode;

// Test value entered
		if(row.children[0].children[0].value!='') {
			var sectionrowslength=tbody.rows.length
			oldaddrow=tbody.rows[sectionrowslength-1];
			newaddrow=row.cloneNode(true);
			n=setLastTestNumber();
			var idprefix=table.id+'_'+n;
			var nameprefix='report[detail_bodypart_test]['+n+']';

// Where is the current bodypart id from this element?
			var bilateral = document.getElementById('bilateral'+bodypart.id);
// Is this Comparitive
			var compreportdate = document.getElementById('rhcompreportdate');

			oldaddrow.cells[0].children[0].id=idprefix+'_rdbtrtname'; 
			oldaddrow.cells[0].children[0].name=nameprefix+'[rdbtrtname]'; 
			oldaddrow.cells[1].children[0].id=idprefix+'_result1_m1_right'; 
			oldaddrow.cells[1].children[0].name=nameprefix+'[rdbtresult1][m1][right]'; 


			if(bilateral.value=='1') {
				oldaddrow.cells[2].children[0].id=idprefix+'_result1_m1_left'; 
				oldaddrow.cells[2].children[0].name=nameprefix+'[rdbtresult1][m1][left]'; 
				if(compreportdate.value!='') {
					oldaddrow.cells[3].children[0].id=idprefix+'_result2_m1_right'; 
					oldaddrow.cells[3].children[0].name=nameprefix+'[rdbtresult2][m1][right]'; 
					oldaddrow.cells[4].children[0].id=idprefix+'_result2_m1_left'; 
					oldaddrow.cells[4].children[0].name=nameprefix+'[rdbtresult2][m1][left]'; 
					oldaddrow.cells[5].children[0].id=idprefix+'_rdbtrtmname'; 
					oldaddrow.cells[5].children[0].name=nameprefix+'[rdbtrtmname]'; 
					InjuryTableRowFormat(oldaddrow.cells[6]);
					oldaddrow.cells[6].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[6].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[6].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[6].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
				else {
					oldaddrow.cells[3].children[0].id=idprefix+'_rdbtrtmname'; 
					oldaddrow.cells[3].children[0].name=nameprefix+'[rdbtrtmname]'; 
					InjuryTableRowFormat(oldaddrow.cells[4]);
					oldaddrow.cells[4].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[4].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[4].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[4].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
			}
			else {
				if(compreportdate.value!='') {
					oldaddrow.cells[2].children[0].id=idprefix+'_result2_m1_right'; 
					oldaddrow.cells[2].children[0].name=nameprefix+'[rdbtresult2][m1][right]'; 
					oldaddrow.cells[3].children[0].id=idprefix+'_rdbtrtmname'; 
					oldaddrow.cells[3].children[0].name=nameprefix+'[rdbtrtmname]'; 
					InjuryTableRowFormat(oldaddrow.cells[4]);
					oldaddrow.cells[4].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[4].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[4].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[4].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
				else {
					oldaddrow.cells[2].children[0].id=idprefix+'_rdbtrtmname'; 
					oldaddrow.cells[2].children[0].name=nameprefix+'[rdbtrtmname]'; 
					InjuryTableRowFormat(oldaddrow.cells[3]);
					oldaddrow.cells[3].children[2].id=idprefix+'_rdbtbcode'; 
					oldaddrow.cells[3].children[2].name=nameprefix+'[rdbtbcode]'; 
					oldaddrow.cells[3].children[3].id=idprefix+'_rdbtrrtgid'; 
					oldaddrow.cells[3].children[3].name=nameprefix+'[rdbtrtgid]'; 
				}
			}
			oldaddrow.id=idprefix;
			eventcount=oldaddrow.cells.length-1;
			for(i=0; i<eventcount; i++) {
				if( document.attachEvent ) {
					newaddrow.children[i].children[0].attachEvent( 'onkeyup', cAutocomplete.complete )
				}
				else if( document.addEventListener ) {
					newaddrow.children[i].children[0].addEventListener( 'keyup', cAutocomplete.complete, false )
				}
				newaddrow.children[i].children[0].value='';
			}
			newaddrow.children[i].children[0].value=oldaddrow.cells[i].children[0].value; 
			newaddrow.children[i].children[1].value=oldaddrow.cells[i].children[1].value; 
			tbody.appendChild(newaddrow);
		}

	}
	catch(e) {
		alert(e);
	}
}


// AROM 1
function AROMTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function AROMTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function AROMTableRowAdd(element) {
	TableRowAdd222(element);
}

// PROM 2
function PROMTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function PROMTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function PROMTableRowAdd(element) {
	TableRowAdd222(element);
}

// GIRTH 3
function GIRTHTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function GIRTHTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function GIRTHTableRowAdd(element) {
	TableRowAdd212(element);
}

// SPECIAL 4
function SPECIALTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function SPECIALTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function SPECIALTableRowAdd(element) {
	TableRowAdd112(element);
}

// MUSCLE 5
function MUSCLETableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function MUSCLETableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function MUSCLETableRowAdd(element) {
	TableRowAdd112(element);
}

// MOBILITY 6
function MOBILITYTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function MOBILITYTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function MOBILITYTableRowAdd(element) {
	TableRowAdd142(element);
}

// MYOTOMES 7
function MYOTOMESTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function MYOTOMESTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function MYOTOMESTableRowAdd(element) {
	TableRowAdd112(element);
}

// DERMATOMES 8
function DERMATOMESTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function DERMATOMESTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function DERMATOMESTableRowAdd(element) {
	TableRowAdd112(element);
}

// REFLEXES 9
function REFLEXESTableRowFormat(element) {
	try{
		var add=element.children[0].style;
		add.display="none";
		var del=element.children[1].style;
		del.display="block";
	}
	catch(e) {
		alert(e);
	}
}
function REFLEXESTableRowDel(element) {
	try {
		var row=element.parentNode.parentNode;
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}
function REFLEXESTableRowAdd(element) {
	TableRowAdd112(element);
}


function changeInputValueToTextNode(inputelement) {
// Replace innerhtml <input> of td with textvlue 
	if(inputelement.value != '') {
		var newTX = document.createTextNode(inputelement.value)
		inputelement.parentNode.replaceChild(newTX,inputelement);
 		return newTX;
 	}
	else
		return(false);
}

function changeInputValueToDelete(inputelement) {
	if(inputelement.innerHTML == 'add') {
		inputelement.textContent='del';
 		return(true);
 	}
	else
		return(false);
}


function TableDel(row) {
	try {
		var table=row.parentNode.parentNode;
		table.deleteRow(row.rowIndex);
	}
	catch(e) {
		alert(e);
	}
}

function TableClick(row) {
	try {
		var clicktype=row.cells[row.cells.length-1].textContent;
		var clicktype=trim(clicktype);
		if(clicktype=='add') {
			var rowtype=row.className;
			if(rowtype=='ROM') 
				ROMTableAddRow(row);
			if(rowtype=='INJURY') 
				InjuryTableAddRow(row);
		}
		else {
			if(clicktype=='del')
				TableDel(row);
		}
	}
	catch(e) {
		alert(e);
	}
}
</script>
<script>
function removetest(n){
	var td=n.parentNode;
	var tr=td.parentNode;
	var table=tr.parentNode;
	table.removeChild(tr);
}

function addtest(tableid) {
	var table=document.getElementById(tableid);
}

// Javascript Toggle Div Function
function toggleaction_bodypart(checkboxid, divtotoggle_id, toggler_id, toggler_hidehtml, toggler_showhtml) {
	var cb = document.getElementById(checkboxid);
	var text = document.getElementById(toggler_id+'_text');
	var div = document.getElementById(divtotoggle_id);
	if(cb.checked) {
		text.style.color='#FFFFFF';
		if(div.style.display=="none") {
			toggleaction(divtotoggle_id, toggler_id, toggler_hidehtml, toggler_showhtml);
		}
	}
	else {
		text.style.color='#AAAAAA';
		if(div.style.display=="block" || div.style.display=="") {
			toggleaction(divtotoggle_id, toggler_id, toggler_hidehtml, toggler_showhtml);
		}
	}
}

function togglediv_bodypart_type(checkboxid, divtotoggle_id, toggler_id, toggler_hidehtml, toggler_showhtml) {
	var cb = document.getElementById(checkboxid);
	var text = document.getElementById(toggler_id+'_text');
	var div = document.getElementById(divtotoggle_id);
	if(cb.checked) {
		text.style.color='#FFFFFF';
		if(div.style.display=="none") {
			toggleaction(divtotoggle_id, toggler_id, toggler_hidehtml, toggler_showhtml);
		}
	}
	else {
		text.style.color='#AAAAAA';
		if(div.style.display=="block" || div.style.display=="") {
			toggleaction(divtotoggle_id, toggler_id, toggler_hidehtml, toggler_showhtml);
		}
	}
}

function toggleaction(divtotoggle_id, toggler_id, toggler_hidehtml, toggler_showhtml) {
// Toggles the twirl action
	var div = document.getElementById(divtotoggle_id);
	var img = document.getElementById(toggler_id);

// Needs to handle toggle for body or group or whatever...
	var id_prefix=img.className;
	
	var x = document.getElementById(id_prefix+'expanded'+toggler_id);

	if(div.style.display == "block" || div.style.display=="") {
// collapse
    	div.style.display = "none";
		img.innerHTML = toggler_showhtml; // '<img src="/img/plus.png">';
		x.value="0";
  	}
	else {
// expand
		div.style.display = "block";
		img.innerHTML = toggler_hidehtml; //'<img src="/img/minus.png">';
		x.value="1";
		var check=document.getElementById(id_prefix+'checked'+toggler_id);
		if(check.checked!=true) 
			check.checked=true;
	}
}

function togglediv(id) {
	var img = document.getElementById(id);
	var divid=id+"_div";
	var div = document.getElementById(divid);
	if(div.style.display == "block" || div.style.display=="") {
    	div.style.display = "none";
		img.innerHTML = '<img src="/img/collapse.gif">';
  	}
	else {
		div.style.display = "block";
		img.innerHTML = '<img src="/img/expand.gif">';
	}
}
</script>

<!-- Begin of Objective HTML -->
<div class="clearboth"></div>
<div class="position_relative" id="OBJ">
	<!--  General Information Section -->
	<div id="general_wrap"> <span id="general" onclick="javascript:toggleaction('general_div', 'general', '<img src=/img/collapse.gif>','<img src=/img/expand.gif>');"><img src="/img/collapse.gif"></span>
		<input type="checkbox" id="rhobjectivenoteprint" name="report[header][rhobjectivenoteprint]" <?php if($report['header']['rhobjectivenoteprint']=='1') echo 'checked="checked"'; ?> />
		Notes &amp; Vitals </div>
	<div class="clearboth"></div>
	<div id="general_div">
		<!--  Objective Note -->
		<div class="position_relative" >
			<div class="col20pct">Objective Note</div>
			<div class="col70pct">
				<textarea cols="60" rows="3" id="rhobjectivenote" name="report[header][rhobjectivenote]"><?php echo $report['header']['rhobjectivenote'] ?></textarea>
			</div>
		</div>
		<div class="clearboth" ></div>
		<div class="position_relative" >
			<div class="col20pct">Blood Pressure</div>
			<div class="col80pct">
				<input id="rhbloodpressure" name="report[header][rhbloodpressure]" value="<?php echo $report['header']['rhbloodpressure'] ?>" />
				Systolic/Distolic </div>
		</div>
		<div class="clearboth" ></div>
		<div class="position_relative" >
			<div class="col20pct">Heart Rate</div>
			<div class="col80pct">
				<input id="rhheartrate" name="report[header][rhheartrate]" value="<?php echo $report['header']['rhheartrate'] ?>" />
				Beats per Minute </div>
		</div>
		<div class="clearboth" ></div>
		<div class="position_relative" >
			<hr />
		</div>
	</div>
	<!--  General Information Section -->
<?php
// Load detail_bodypart & group array values

// create reference for tests by bodypart code 

//dump("report['detail_bodypart']",$report['detail_bodypart']);
//exit();

//if(is_array($report['detail_bodypart']))
//	foreach($report['detail_bodypart'] as $recid=>$testarray) 
//		$bodypartindex[$testarray['rdbbcode']][]=$testarray['rdbid'];
if(is_array($report['detail_bodypart']))
	foreach($report['detail_bodypart'] as $recid=>$testarray) 
		$bodypartindex[$testarray['rdbbcode']]=$testarray['rdbid'];

// create reference for tests by bodypart code and group to id
if(is_array($report['detail_bodypart_test']))
	foreach($report['detail_bodypart_test'] as $recid=>$testarray) 
		$testindex[$testarray['rdbtbcode']][$testarray['rdbtrtgid']][]=$testarray['rdbtid'];

// if report detail bodypart contains information...
//if(is_array($report['detail_bodypart']) && count($report['detail_bodypart'])>0 ) {
//	// copy report_detail_bodypart array keyed by bcode
//	foreach($report['detail_bodypart'] as $rdbid=>$bodypartarray) {
//		$bp=$bodypartarray['rdbbcode'];
//		$bd=$bodypartarray['rdbsdescription'];
//		foreach($report['detail_bodypart_test_group']["$bp"] as $rtgid=>$grouparray) {
//			$gp=$bodypartarray['rdbtgrtgid'];
//			$gd=$bodypartarray['rtgname'];
//// if report detail bodypart test contains information...
//			if(is_array($report['detail_bodypart_test']["$bp"]["$gp"]) && count($report['detail_bodypart_test']["$bp"]["$gp"])>0 ) {
//// copy report_detail_bodypart_test array into $detail_bodypart array
//			unset($detail_bodypart_test);

//			$grouparray=array();
//			foreach($report['detail_bodypart_test'] as $dispseq=>$bodyparttestarray) {
//				if($bodyparttestarray['rdbtbcode']==$bp) {
//					$rtid=$bodyparttestarray['rdbtrtid'];
//					$rtname=$bodyparttestarray['rtname'];
//					$rtrtgid=$bodyparttestarray['rtrtgid'];
//					if($bodyparttestarray["rdbtchecked"]==1)
//						$grouparray["$rtrtgid"]['checked']=1;
//					if($bodyparttestarray["rdbtexpanded"]==1)
//						$grouparray["$rtrtgid"]['expanded']=1;
//					$detail_bodypart_test["$rtid"]=$bodyparttestarray;
//				}
//			}

//			if(is_array($detail_bodypart_test) && count($detail_bodypart_test)>0) {
//				$bodypartarray['tests']=$detail_bodypart_test;
//				$bodypartarray['groups']=$grouparray;
//			}
//			else {
//				$bodypartarray['tests']=array();
//				$bodypartarray['groups']=array();
//			}
//		}
//		$detail_bodypart["$rdbbcode"]=$bodypartarray;
//	}
//}

// for each valid bodypart (about 13 of them)
//$bodyparts=getReportBodypartsOptions(); // Get all Bodyparts
//dump('bodyparts',$bodyparts);
$lasttestnumber=0;
foreach($bodyparts as $bp=>$detail_bodypartarray) {
	$array=$report['detail_bodypart'][$bodypartindex["$bp"]];
	$bd=$detail_bodypartarray['rbsdescription'];//.'-'.$detail_bodypartarray['rbdescription'];
	$bilateral=$detail_bodypartarray['rbbilatflag'];
	// Get the the expanded/collapsed status of the bodypart
			if($array['rdbexpanded']=='1') {
				$expandimg='<img src="/img/collapse.gif">';
				$expandstyle='style="display:block;"';
				$expandvalue='1';
			}
			else {
				$expandimg='<img src="/img/expand.gif">';
				$expandstyle='style="display:none;"';
				$expandvalue='0';
			}
	
	// Get the the checked/unchecked status of the bodypart
			if(strtolower($array['rdbchecked'])=='on') 
				$checked='checked="on"';
			else
				$checked='';

// detail bodypart record contains details for displaying and controlling twirl and checkbox
			$bodypartdivid="bodypart:$bp:div";
			$bodypartspanid="bodypart:$bp:span";
			$testgroupdivid="bodypart:$bp:group:$gp:div";
?>
<!--  BODYPART <?php echo $bd; ?> Section START -->
	<div class="clearboth"></div>
	<div class="position_relative" id="<?php echo "$bp"; ?>">
<!-- START OF <?php echo "$bp $bd"; ?> DIV -->
		<div id="<?php echo $bodypartdivid; ?>">
			<span class="rdb" id="<?php echo $bodypartspanid; ?>" onclick="javascript:toggleaction('<?php echo $testgroupdivid; ?>', '<?php echo $bodypartspanid; ?>', '<img src=/img/collapse.gif>','<img src=/img/expand.gif>');"><?php echo $expandimg; ?></span>
			<input type="hidden" id="bilateral<?php echo $bp; ?>" name="report[detail_bodypart][<?php echo $bp; ?>][bilateral]" value="<?php echo $bilateral; ?>"  />
			<input type="hidden" id="rdbbcode<?php echo $bp; ?>" name="report[detail_bodypart][<?php echo $bp; ?>][rdbbcode]" value="<?php echo $bp; ?>"  />
			<input type="hidden" id="rdbsdescription<?php echo $bp; ?>" name="report[detail_bodypart][<?php echo $bp; ?>][rdbsdescription]" value="<?php echo $bd; ?>"  />
			<input type="hidden" id="rdbexpanded<?php echo $bodypartspanid; ?>" name="report[detail_bodypart][<?php echo $bp; ?>][rdbexpanded]" value="<?php echo $expandvalue; ?>"  />
			<input type="checkbox" id="rdbchecked<?php echo $bodypartspanid; ?>" name="report[detail_bodypart][<?php echo $bp; ?>][rdbchecked]" <?php echo $checked; ?> /><?php echo $bd; ?></div>
		<div id="<?php echo $testgroupdivid; ?>" <?php echo $expandstyle; ?> >


<!--  BODYPART TESTS Section -->
<?php

// Get the valid test groups for this body part
$bodyparttestgroups=getBodypartTestGroups($bp);
	foreach($bodyparttestgroups as $index=>$bodyparttestgroup) {
		$gp=$bodyparttestgroup['rtgid'];
		$gd=$bodyparttestgroup['rtgname'];
		$bg="$bp:$gp";

		$detail_bodypart_test_group=$report['detail_bodypart_test_group']["$bp"]["$gp"];

// Get the the expanded/collapsed status of the bodypart test
		if($detail_bodypart_test_group['rdbtgexpanded']=='1') {
			$expandimg='<img src="/img/collapse.gif">';
			$expandstyle='style="display:block;"';
			$expandvalue='1';
		}
		else {
			$expandimg='<img src="/img/expand.gif">';
			$expandstyle='style="display:none;"';
			$expandvalue='0';
		}

// Get the the checked/unchecked status of the bodypart test
		if(strtolower($detail_bodypart_test_group['rdbtgchecked'])=='on') 
			$checked='checked="checked"';
		else
			$checked='';

	?>
	<!--  START OF <?php echo "$bp $bd $gp $gd"; ?> DIV -->
				<div id="<?php echo "bodyparttestgroup:$bg:properties"; ?>" style="margin-left:20px">
					<span class="rdbtg" id="<?php echo $bg; ?>" onclick="javascript:toggleaction('<?php echo $bg; ?>!div', '<?php echo $bg; ?>', '<img src=/img/collapse.gif>','<img src=/img/expand.gif>');"><?php echo $expandimg; ?></span>
			<input type="hidden" id="rdbtgbcode<?php echo $bg; ?>" name="report[detail_bodypart_test_group][<?php echo $bp; ?>][<?php echo $gp; ?>][rdbtgbcode]" value="<?php echo $bp; ?>"  />
			<input type="hidden" id="rdbtgrtgid<?php echo $bg; ?>" name="report[detail_bodypart_test_group][<?php echo $bp; ?>][<?php echo $gp; ?>][rdbtgrtgid]" value="<?php echo $gp; ?>"  />
			<input type="hidden" id="rdbtgexpanded<?php echo $bg; ?>" name="report[detail_bodypart_test_group][<?php echo $bp; ?>][<?php echo $gp; ?>][rdbtgexpanded]" value="<?php echo $expandvalue; ?>"  />
					<input type="checkbox" id="rdbtgchecked<?php echo $bg; ?>" name="report[detail_bodypart_test_group][<?php echo $bp; ?>][<?php echo $gp; ?>][rdbtgchecked]" <?php echo $checked; ?> /><?php echo strtoupper($gd); ?></div>
				<div id="<?php echo $bg; ?>!div" <?php if($twirl["$bg"]!='1') echo $expandstyle; ?> >
					<div id="<?php echo "bodyparttestgroup:$bg:tests"; ?>" style="margin-left:40px">
						<?php include('Objective_testtype_'.$gp.'.php'); ?>
					</div>
				</div>
	<!--  END OF <?php echo "$bp $bd $gp $gd"; ?> DIV -->
	<?php
	}
?>
		</div>
	</div>
<!-- END OF <?php echo $bp; ?> DIV -->
<!-- BODYPART <?php echo $bd; ?> Section END -->
<?php
		if(!empty($lasttestnumber)) {
//			dump('OBJlasttestnumber',$lasttestnumber);
			$savelasttestnumber=$lasttestnumber;
		}
}
?>
</div>
<input id="lasttestnumber" name="lasttestnumber" type="hidden" value="<?php echo $savelasttestnumber;?>" />
<div class="clearboth"></div>