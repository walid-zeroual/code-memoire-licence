import { Router } from '@angular/router';
import { Input, DoCheck } from '@angular/core';
import { SigninComponent } from './../signin/signin.component';

import { Component, OnChanges, OnInit, SimpleChanges } from '@angular/core';

import { ApiService } from '../api.service';

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.css']
})
export class NavComponent implements DoCheck  {
   islogin:any

  constructor(private ApiService:ApiService, private signin:SigninComponent,private Router:Router) {
   
    if(localStorage.getItem('getitems')===null){
      localStorage.setItem('getitems','true')
      
    }
    if(localStorage.getItem('username')===null){
      localStorage.setItem('username','student')
    }
    if(localStorage.getItem('islogin')===null){
      localStorage.setItem('islogin','false')
      
    }
    this.islogin=localStorage.getItem('islogin')

   
  }
  ngDoCheck(): void {
    this.islogin=localStorage.getItem('islogin')
    
  }

  
 
  
  
 getcourses(){   
   this.ApiService.getcourses()
   setTimeout(() => {
     this.Router.navigate(['/courses'])
   }, 1500);


 }
  
  
 
 
 
 
  logout(){
    localStorage.setItem('islogin','false')  
    localStorage.setItem('username','student')
      
      this.ApiService.logout();
      this.Router.navigate([''])
      
     

  }
   sendcontent(){
   
   let send
    var value=(<HTMLInputElement>document.getElementById('search')).value
    send=value
    console.log(send)
    this.ApiService.contentrecomndation(send)
    localStorage.setItem('search',send)
    setTimeout(() => {
      let rout='/content'
        this.Router.navigate([rout]) 
       
    },1500);
  
 

 

  }
   
  showmenu(){
    let x= document.getElementById("sidenav");
 let name=x?.className;
  if (name==='side-nav-close'){
    x?.classList.replace('side-nav-close','side-nav-open');
    
  }  if (name==='side-nav-open') {
    x?.classList.replace('side-nav-open','side-nav-close');
    
  }  
  
 
 //    sendcontentbyenter(){
//      let value
//      let send
//      let x=(<HTMLInputElement>document.getElementById('search'))
//      x.addEventListener("keypress", function($event) {
     
//       if ($event.key === "Enter") {
//         value=x.value
       
//        } 
      

//     });
//     send={search:value}
//  console.log(send)
//     this.ApiService.contentrecomndation(send)

//    }
  

   

 
 
  
  
  }
}
