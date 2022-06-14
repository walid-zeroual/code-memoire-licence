import { BarRatingModule } from 'ngx-bar-rating';
import { Component, OnInit,Input } from '@angular/core';

@Component({
  selector: 'app-rating',
  templateUrl: './rating.component.html',
  styleUrls: ['./rating.component.css']
})
export class RatingComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }
  @Input() c=false;
 
  x=10;
 isactive=false;
  
  
clicked(){

  
 this.c=!this.c;
 if(this.c===true){this.x++}
 else if (this.c===false){this.x--}



}



}
