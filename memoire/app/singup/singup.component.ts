
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ApiService } from '../api.service';
import { FormControl,FormGroup,Validators } from '@angular/forms';


@Component({
  selector: 'app-singup',
  templateUrl: './singup.component.html',
  styleUrls: ['./singup.component.css']
})

 
export class SignupComponent implements OnInit {
  formL= new FormGroup({
    user:new FormControl('',Validators.required),
    email:new FormControl('',Validators.required),
    password:new FormControl('',[
      Validators.required,
      Validators.minLength(3),
      Validators.maxLength(15)
    ])
    
   
    
  })
 
constructor(private dataService: ApiService, private router:Router) {

}
get user(){
  return this.formL.get('user')
}
get password(){
return this.formL.get('password')
}
get email(){
  return this.formL.get('email')
  }

get() {
  
  setTimeout(() =>{
    
 var y= (this.dataService.R['register'])
  
    if(y=='true'){
      
      let router='/signin'
      this.router.navigate([router])}
     else if(y==='false') {
     this.formL.setErrors({
      alreadyused: 'true'
     })
    }
    
}, 1000);
}


ngOnInit() {

}
sendData(){

 
  let u=(<HTMLInputElement>document.getElementById('user_name')).value
   let e=(<HTMLInputElement>document.getElementById('E_mail')).value
   let p=(<HTMLInputElement>document.getElementById('pass_word')).value
let x = {
  register:'true',
  email:e,
  username:u,
  password:p
};

   this.dataService.userRegistration(x)
}  


}

