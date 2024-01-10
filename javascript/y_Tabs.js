/* Compiled by XC 1.06 on 28Aug07 */
ylib.namespace('ylib.widget');

ylib.widget.Tabs=function(aHead,aContent,startIndex,trackingElementID){
	this.activeIndex=startIndex?startIndex:0;
	this.aH=aHead?aHead:[];
	this.aB=aContent?aContent:[];
	this.tracker=trackingElementID?trackingElementID:'';
	this.AttachEvents();
	this.ShowTabs();
};

ylib.widget.Tabs.prototype.AttachEvents=function(){
	var elem;
	for(var i=0;i<this.aH.length;i++){
		elem=xGetElementById(this.aH[i]);
		elem.onclick=this.ActivateTab;
		elem.tabObj=this;
	}
};

ylib.widget.Tabs.prototype.ShowTabs=function(){
	var elem;
	for(var i=0;i<this.aH.length;i++){
		elem=xGetElementById(this.aH[i]);
		if(i==this.activeIndex){
			elem.className='active';
		}
		else{
			elem.className='';
		}
	}
	for(var i=0;i<this.aB.length;i++){
		elem=xGetElementById(this.aB[i]);
		if(i==this.activeIndex){
			xDisplay(elem,'');
			xVisibility(elem,1);
		}
		else{
			xDisplay(elem,'none');
		}
	}
	elem=xGetElementById(this.tracker);
	if(elem){
		elem.value=this.activeIndex;
	}
};

ylib.widget.Tabs.prototype.ActivateTab=function(event){
	var thisID=this.id;
	var obj=this.tabObj;
	for(var i=0;i<obj.aH.length;i++){
		if(thisID==obj.aH[i]){
			obj.activeIndex=i;obj.ShowTabs();
			return;
		}
	}
};
