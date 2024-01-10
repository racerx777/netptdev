PhpUnit is a testing framework for PHP, similar to JUnit for Java. 
It makes sure our code is and stays stable.

Finding bugs is a developers daily business. It can be a big pain, 
and it's even worse if something was once running, and/or you don't even 
know where it's broken. The bigger your application, the worse it is.

That's why we use ecg (heartbeat) tests for all critical code 
(and usually for the others, too).

Example: there is the method core/Util/Bs_Array->merge(). Maybe in 
         the future php's array handling will be changed somehow. 
         And such changes may need a rewrite of this method. But 
         how could we tell? 
         There is the subdirectory core/Util/ecg/ with a class 
         Bs_Array_PhpUnit.class.php. For many other classes, such 
         subdirectories with corresponding classes exist. Now if 
         we install a new PHP version, or if something is broken, 
         all we have to do is call one PHP file that starts all 
         our unit tests, and shows what went wrong, and why. 

another advantage is that when writing the tests, we do that by calling 
our new methods with many different params. also things that makes 
no sense. this way we detect possible bugs already while coding, not 
months later in a productional environment.



