var nS=new Array;var hS=new Array;var nL=new Array;var hL=new Array;var nTCode=new Array;var AnimStep=0;var AnimHnd=0;var HTHnd=new Array;var DoFormsTweak=true;var MenusReady=false;var SelSndId=0;var NSFixedFonts=false;var nsOW;var nsOH;var mFrame;var cFrame;var OpenMenus=new Array;var nOM=0;var mX;var mY;var HideSpeed=300;var TimerHideDelay=2000;var TimerHideHnd=0;var IsOverHS=false;var cntxMenu='Documentation';var IsContext=false;var IsFrames=false;var BV=parseFloat(navigator.appVersion.indexOf("MSIE")>0?navigator.appVersion.split(";")[1].substr(6):navigator.appVersion);var BN=navigator.appName;var IsWin=(navigator.userAgent.indexOf('Win')!=-1);var IsMac=(navigator.userAgent.indexOf('Mac')!=-1);var OP=(navigator.userAgent.indexOf('Opera')!=-1&&BV>=4)?true:false;var NS=(BN.indexOf('Netscape')!=-1&&(BV>=4&&BV<5)&&!OP)?true:false;var SM=(BN.indexOf('Netscape')!=-1&&(BV>=5)||OP)?true:false;var IE=(BN.indexOf('Explorer')!=-1&&(BV>=4)||SM)?true:false;if(!eval(frames['self'])){frames.self=window;frames.top=top;}cFrame=eval(frames['self']);var fx=2;nL[1]="%22/