import { Router } from '@angular/router';
import { ApiService } from './../api.service';
import { Component, OnInit, DoCheck } from '@angular/core';

@Component({
  selector: 'app-sidenav',
  templateUrl: './sidenav.component.html',
  styleUrls: ['./sidenav.component.css']
})
export class SidenavComponent implements OnInit ,DoCheck {
  islogin:any
  constructor(private ApiService:ApiService,private Router:Router) { }

  ngOnInit(): void {
  }
  ngDoCheck(): void {
    this.islogin=localStorage.getItem('islogin')
    
  }
  sendcontent(){
   
    let send
     var value=(<HTMLInputElement>document.getElementById('side-search')).value
     send=value
     console.log(send)
     this.ApiService.contentrecomndation(send)
     localStorage.setItem('search',send)
     setTimeout(() => {
       let rout='/content'
         this.Router.navigate([rout]) 
        
     },1500);}
  
  
  showmenu(){
    let x= document.getElementById("sidenav");
 x?.classList.replace('side-nav-open','side-nav-close');
  
   
  }
  getcourses() {
    this.ApiService.getcourses()
    setTimeout(() => {
      this.Router.navigate(['/courses'])
    }, 1500);
  }
  logout(){
    localStorage.setItem('islogin','false')  
    
    
      if(localStorage.getItem('islogin')=='true'){
         document.getElementById('logup')?.style.display==='none'
      }
      
       window.location.reload();
  }
 
}
