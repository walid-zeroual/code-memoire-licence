import { Component, OnInit, DoCheck } from '@angular/core';
import { ApiService } from '../api.service';
@Component({
  selector: 'app-recomnded',
  templateUrl: './recomnded.component.html',
  styleUrls: ['./recomnded.component.css']
})
export class RecomndedComponent implements OnInit,DoCheck {



  ngOnInit(): void {
    let x={recommend:"true", username: localStorage.getItem('username')}
    this.ApiService.recomended(x)
  }
  items:any
  finaldata:any
  x:any
   constructor(private ApiService:ApiService) {}
     
     
       
   
  
   ngDoCheck() {
   this.items=this.ApiService.Ritems
   this.finaldata = Object.keys(this.items).map(key => ({name: key, rating:this.items[key]}));
   if (typeof(this.items)==='object'){
    this.x=0
    console.log('x is 0')
 
  }
  else if (typeof(this.items)==='string') {
 
    (this.x=1)
    console.log('x is 1')
  }
      
    }
}
