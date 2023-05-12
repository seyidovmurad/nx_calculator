$(document).ready(function() {
  
  var eq = $('#previous').val();
  var curNumber=$('#previous').val();
  var result = $('#previous').val();
  var entry = $('#previous').val();
  var reset = false;

  $("button").click(function() {  
    entry = $(this).attr("value");   
    
    if (entry === "ac") {
      reset=true;
      eq=0;
      result=0;
      curNumber=0;
      $('#previous').val(eq);  
    }
    
    else if (entry === "ce") {
      if (eq.length > 1) {
        if(eq.endsWith('[value]')) {
          eq = eq.slice(0, -'[value]'.length);
        }
        else {
          eq = eq.slice(0, -1); 
         
        }
      }
      else {
        reset=true;
        eq = 0; 
      }
      $('#previous').val(eq);
      
      if (curNumber.length > 1) {
        curNumber = curNumber.slice(0, -1); 
      }
      else {
        curNumber = 0;  
      }
      
    }
    
    else if (entry === "=") {
      if (curNumber === 0 || eq == 0) { 
        curNumber = "[value]";
        eq = "[value]";         
      }
      else
        eq += "[value]";
      $('#previous').val(eq);
      
    }
    
    else if (isNaN(entry)) {   //check if is not a number, and after that, prevents for multiple "." to enter the same number
      if (entry !== ".") {     
        reset = false;       
        if (curNumber === 0 || eq == 0) { 
          curNumber = entry;
          eq = entry;         
        }
        else {
          curNumber = "";
          eq += entry;
        }     
        $('#previous').val(eq); 
      }
      else if (curNumber.indexOf(".") === -1) { 
        reset = false;
        if (curNumber === 0 || eq == 0) { 
          curNumber = 0.;
          eq = 0.;           
        }
        else {
          curNumber += entry;
          eq += entry;        
        }
        $('#previous').val(eq);        
      }      
    }
        
    else {  
      if (reset) {
        eq = entry;
        curNumber = entry;       
        reset = false;
      }
      else {
        eq += entry; 
        curNumber += entry;        
        }
       $('#previous').val(eq); 
      }   
    
    
    
    
    if (result.indexOf(".") !== -1) {
      result = result.truncate()
    }
    
  });
    

});