// JavaScript Documentvar mnAutoSaveMilliSeconds=0;
  var mnAutoSaveMilliSecondsExp=0;
  var mnAutoSaveInterval=30000;

  function AutoSaveInit(nMilliSeconds)
  {

     try
     {

       var nMinutes=0;
         
       AutoSaveClearTimeOuts();

       nMinutes = ((nMilliSeconds / 1000) / 60); 
       mnAutoSaveMilliSeconds = nMilliSeconds; 
       mnAutoSaveMilliSecondsExp=0;

       oTimeOut = window.setTimeout("AutoSaveSubmit()",nMilliSeconds);
       oInterval = window.setInterval("AutoSaveCountDown()",mnAutoSaveInterval);
       document.getElementById("divAutoSave").innerHTML = "<b>Auto Save In " + nMinutes + " Minutes</b>";
		
      }
      catch (exception) 
      { 
        if (exception.description == null) { alert("AutoSaveInit Error: " + exception.message); }  
        else {  alert("AutoSaveInit Error: " + exception.description); }
      }
  }

  function AutoSaveCountDown()
  {

    var nMinutesLeft=0;
    var nMilliSecondsLeft=0;

    mnAutoSaveMilliSecondsExp =  mnAutoSaveMilliSecondsExp + mnAutoSaveInterval;

    if ( mnAutoSaveMilliSeconds > mnAutoSaveMilliSecondsExp)
    {
      nMilliSecondsLeft = mnAutoSaveMilliSeconds - mnAutoSaveMilliSecondsExp;
      nMinutes= AutoSaveRoundNumber(((nMilliSecondsLeft / 1000) / 60),2); 
      document.getElementById("divAutoSave").innerHTML = "<b>Auto Save In " + nMinutes + " Minutes</b>";
    }

  }

  function AutoSaveBeforeSubmit()
  {
     document.getElementById("divAutoSave").innerHTML = '<b>Saving data...Please wait.</b>';
     return true;
  }


  function AutoSaveClearTimeOuts()
  {
    try
    {
		if(oInterval)
			window.clearInterval(oInterval);
		if(oTimeOut)
			window.clearTimeout(oTimeOut);
    }
    catch (exception) { }

  }

  function AutoSaveSubmit()
  {
    try
    {
      AutoSaveClearTimeOuts();
      AutoSaveBeforeSubmit();
	
      /*
         Call the form submittal code in your main page.
      */
      SubmitFormToBeSaved();
    
     }
    catch (exception) {}
  }

   function AutoSaveRoundNumber(number,X)
  {
	  
    var number2;
    var TmpNum;

     X=(!X ? 1:X);
	
     number2 = Math.round(number*Math.pow(10,X))/Math.pow(10,X);
     TmpNum = "" + number2;
     var TmpArray = TmpNum.split(".");
     if (TmpArray.length <2) { number2 = number2 + ".0"; }
	 
     return number2;
  }