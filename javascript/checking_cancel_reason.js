  /*Start of form validation: */
  function validateForm(formElement) {
	 
	  
	 //Make sure a location is selected
    if (formElement.reason_cancel.selectedIndex == 0)
      return focusElement(formElement.reason_cancel,
       'Please select your reason.');

    if (formElement.reason_cancel.selectedIndex == 3)
	  { 
    //Check user name is at least 2 characters long
      if (formElement.reason_cancel2.value.length < 2)
	  return focusElement(formElement.reason_cancel2,
      'Please enter your reason.');
	  
	  }
	 
	  //Make sure a location is selected
    if (formElement.reason_close.selectedIndex == 0)
    return focusElement(formElement.reason_close,
    'Please select your reason.');
	 
    if (formElement.reason_close.selectedIndex == 5)
    { 
    //Check user name is at least 2 characters long
     if (formElement.reason_close2.value.length < 2)
	 return focusElement(formElement.reason_close2,
     'Please enter your reason.');
	 }
   
    //If all is OK, return true and let the form submit
    return true;
	
	
  }
  /*End of form validation.*/

  
