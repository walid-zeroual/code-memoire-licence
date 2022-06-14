import { NavComponent } from './../nav/nav.component';
import { waitForAsync } from '@angular/core/testing';

import { ApiService } from './../api.service';
import { Component, Input, OnInit, OnChanges, SimpleChanges, Injectable, DoCheck } from '@angular/core';
import { ChangeDetectorRef } from '@angular/core';

@Component({
  
  selector: 'app-content',
  templateUrl: './content.component.html',
  styleUrls: ['./content.component.css']
})
export class ContentComponent implements DoCheck,OnInit{
 
 items:any
 finaldata:any
 x:any
  constructor(private ApiService:ApiService) {
    
    
      
  }
  ngOnInit(): void {
    this.ApiService.contentrecomndation(localStorage.getItem('search'))
 
  }
  ngDoCheck() {
    this.items=this.ApiService.contentitems
    this.finaldata = Object.keys(this.items).map(key => ({name: key, rating:this.items[key]}));
    if (Object.keys(this.items).length===0){
      this.x=0
    
    }
    else if (Object.keys(this.items).length!==0) {(this.x=1)
    
    }
  
   }
      
        
      
      
     
    
  
  }

  
    
    
   
    //  setTimeout(() => {
    //   console.log (this.items=((this.ApiService.contentitems)))
    //   this.finaldata = Object.keys(this.items).map(key => ({name: key, rating:this.items[key]}));
    
    
  //  }, 2500);
  
  

  // getnewitem(x:object){
  //   setTimeout(() => {
  //     console.log (this.items=((this.ApiService.contentitems)))
  //   this.finaldata = Object.keys(this.items).map(key => ({name: key, rating:this.items[key]}));
  //   }, 2500);
    
  // }
  

 


 
  


