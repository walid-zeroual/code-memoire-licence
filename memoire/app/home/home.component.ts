import { Router } from '@angular/router';
import { ApiService } from './../api.service';
import { Component, OnInit, DoCheck } from '@angular/core';
import { CarouselModule } from 'ngx-bootstrap/carousel';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';  

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit,DoCheck {
 islogin:any
  constructor(private Router:Router,private ApiService:ApiService) { 
    
    if(localStorage.getItem('getitems')===null){
      localStorage.setItem('getitems','true')
      
    }
    if(localStorage.getItem('username')===null){
      localStorage.setItem('username','student')
    }
    if(localStorage.getItem('islogin')===null){
      localStorage.setItem('islogin','false')
      
    }

  }
  ngDoCheck(): void {
    this.islogin=localStorage.getItem('islogin')
    
  }

  ngOnInit(): void {
  }
  router(){
    this.ApiService.getcourses()
   
       this.Router.navigate(['/courses'])
    };
   
  }


