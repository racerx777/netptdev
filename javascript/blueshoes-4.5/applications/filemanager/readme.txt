README for the BlueShoes FileManager Application
================================================


file manager settings:

basePath:          this path will be browsable in the file manager. if you want to make the 
                   image dir of your website browsable, specify 
                   $_SERVER['DOCUMENT_ROOT'] . 'images/'
                   another example would be "c:/foo/bar/".

showRelative:      if your basePath is "c:/foo/bar/" then showRelative means the user 
                   cannot see that he's in "c:/foo/bar/". he only sees what's in that 
                   folder. this is recommended for security reasons.

maxFileUploadSize: in bytes. note that several technologies have their limits:
                   a) file uploads my be disabled in the php.ini
                   b) the php.ini file has its own max file size definition.
                   c) firewalls and proxies sometimes limit the http post header 
                      (this is where the file is uploaded). it might be set to 
                      10 MB. 

jsBasePath       : the path to the blueshoes javascript directory. if you have 
                   extracted the blueshoes folder to your webroot then this would 
                   be '/blueshoes-4.4/javascript/'. but probably you have copied 
                   the javascript dir to your webroot directy and named it 
                   bsJavascript, then it would be 'bsJavascript'.

imgBasePath      : same as jsBasePath but for the images folder.

language         : the language to use for the GUI. currently 'en' and 'de' are 
                   available. have a look at the 
                   blueshoes-x.x/applications/filemanager/lang/ folder. you can 
                   copy those files and make your own language.


updated: 2003-10-30 for bs-4.5  --andrej

