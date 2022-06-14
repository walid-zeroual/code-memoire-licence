import { FormGroup, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { Component, Input, OnInit, Output } from '@angular/core';
import { ApiService } from '../api.service';
import { Router } from '@angular/router';


@Component({
  selector: 'app-signin',
  templateUrl: './signin.component.html',
  styleUrls: ['./signin.component.css']
})

export class SigninComponent implements OnInit {
 
  public name:any
  public y='false'
  
  form= new FormGroup({
    useroremail:new FormControl('',Validators.required),
    password:new FormControl('',[
      Validators.required,
      Validators.minLength(3),
      Validators.maxLength(15)
    ])
    
   
    
  })
  ngOnInit(): void {
  }
   get() {
  
  setTimeout(() =>{
    
  this.y= (this.dataservice.L['login'])
 
    if(this.y==='true'){
     
      this.router.navigate(['/courses'])
    //  .then(() => {
     //   window.location.reload();
    //  });

      this.name=this.dataservice.L['username']
    
    
        
     
      localStorage.setItem("islogin",this.y)
      localStorage.setItem("username",this.dataservice.L['username'])
     
    
    }
      
        
    
     else if(this.y==='false') {
    this.form.setErrors({
      invalid:true
    })
    }
    
}, 1000);
  
     
  }
  

     
     
    

  constructor(private dataservice:ApiService,private router:Router) { 
   
  }
  get useroremail(){
    return this.form.get('useroremail')
}
get password(){
  return this.form.get('password')
}


  
sendata(){
  let x=(<HTMLInputElement>document.getElementById('nameoremail')).value
  
  let z=(<HTMLInputElement>document.getElementById('password')).value
 
  var user =({login:'true',email:x, username:x, password:z});
this.dataservice.userlogin(user)

  
}


 

}


