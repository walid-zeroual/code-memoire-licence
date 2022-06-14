
import { ApiService } from '../api.service';
import { Component, OnInit, OnChanges, DoCheck, SimpleChanges } from '@angular/core';
import { HttpClient,  } from '@angular/common/http';
import { SigninComponent } from '../signin/signin.component';
import { Router } from '@angular/router';

@Component({
  selector: 'app-courses',
  templateUrl: './courses.component.html',
  styleUrls: ['./courses.component.css']
})
export class CoursesComponent implements DoCheck,OnInit {
 
  items:any
finaldata:any
currentitems:any
  userRate:any
  users :any;
 name:string | unknown
  islogin:any
 x=0;

 page :number=1
 totalitems:any
pagesize=10

  
  

onPageChange(page:number) {
   this.currentitems =  this.finaldata.slice((page-1)*this.pagesize,(page-1)*this.pagesize +this.pagesize );
  
  console.log(this.currentitems)
  
 
  
}

 
 

//  returndata=[{username:<any>'',coursename:'',courserating:''}]
  ngDoCheck(): void {
    this.name=localStorage.getItem('username')
    this.islogin=localStorage.getItem('islogin')
   
  
  
    }


    

  constructor(private HttpClient:HttpClient,private Signin:SigninComponent,private ApiService:ApiService,private Router:Router) { 
    
  }
  ngOnInit(): void {
    this.ApiService.getcourses()
    setTimeout(() => {
    (this.items=((this.ApiService.returnitems)))
    this.finaldata = Object.keys(this.items).map(key => ({name: key, rating:this.items[key]}));
    this.totalitems= Object.keys(this.finaldata).length;
    this.currentitems = this.finaldata.slice((this.page-1)*this.pagesize,(this.page-1)*this.pagesize +this.pagesize );
  
   }, 1500);
  }

 
  // updaterate(coursename:any,e:any){
  
  
 
  // this.returndata.forEach(element => {
  //   if(element.coursename===coursename){

  //      element.courserating=e 
  //      this.x=1

      
  //    } else this.x=0
    
      
     
         
        
  //   })
  //   if (this.x==0){
  //      let newdata={username:this.name,coursename:coursename,courserating:e}
  //        this.returndata.push(newdata)
  //   }
  //   }

   


updateratedb(coursename:any,e:any){
    let updatedata ={username:this.name,coursename:coursename,courserating:e}
    this.ApiService.updatdata(updatedata)
}


returnfinaldata(){
  let x={recommend:"true", username: localStorage.getItem('username')}
 this.ApiService.recomended(x)
 setTimeout(() => {
   this.Router.navigate(['/recommended'])
 }, 1100);
  
}
    }
      
      
     
    
     
    
  
  
  
   
  

  
 
  




                            


  

  



