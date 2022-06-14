

import { Injectable, OnInit, Input } from '@angular/core';
import { HttpClient,HttpHeaders } from '@angular/common/http';



@Injectable({
providedIn: 'root'

})

export class ApiService implements OnInit  {
ngOnInit():any  {
 
 
  
}
  
baseUrl:string = "https://educateplus.000webhostapp.com"
 R:object |any
 L :object |any
 LO :boolean |any
 returnitems:object |any
Ritems:object|any
 contentitems:object|any
 @Input() changevalue:any
 lenght:any

  
 
 headers= new HttpHeaders()
  .set('content-type', 'application/json')
  .set('Access-Control-Allow-Origin', '*')
  .set('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS')
   .set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, X-CSRF-TOKEN,Authorization  X-Requested-With, Accept ')
 .set('Access-Control-Allow-Credentials', 'true')
 .set('Access-Control-Expose-Headers','Content-Disposition, filename')
 .set('Access-Control-Allow-Headers', 'x-requested-with, authorization, cache-control')
 .set('Access-Control-Expose-Headers', 'Authorization')
.set('Cache-Control', 'no-cache')

  

  
constructor(private httpClient : HttpClient,) {
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
getcourses(){
  let I={getitems:'true',username: localStorage.getItem('username')}
  this.httpClient.post(this.baseUrl+'/getitems.php',I,{headers:this.headers})
  .subscribe(Response=> console.log((this.returnitems)=Response))
  if (this.returnitems===NaN)
  {
    console.log('fergha')
  }
}

public logout(){
  let x={logout:'true'}
 return this.httpClient.post(this.baseUrl+'/logout.php',x,{headers:this.headers}).subscribe(Response=>
  (this.LO=(Response)))
}
 

public userRegistration(x:any) {
 
 x=(JSON.stringify(x))

  return this.httpClient.post(this.baseUrl+'/register.php',x,{headers:this.headers}).subscribe(Response=>
    (((this.R=(Response))))
    
   
   )

  
};
public userlogin(x:any) {

  x=(JSON.stringify(x))

this.httpClient.post<any>(this.baseUrl+'/login.php',x,{headers:this.headers}).subscribe(Response=>
  (this.L=(Response)))


  }
  recomended(x:any){
    
    this.httpClient.post<any>(this.baseUrl+'/recommend.php',x,{headers:this.headers}).subscribe(Response=>
   console.log(this.Ritems=(Response) ))
  
   
  }

updatdata(x:any){
  this.httpClient.post<any>(this.baseUrl+'/updaterating.php',x,{headers:this.headers}).subscribe(Response=>
    ((this.L=(Response))))
  
   
}
contentrecomndation(x:any){
  this.changevalue=x
  x={search:x}
  x=(JSON.stringify(x))
 
  this.httpClient.post<any>(this.baseUrl+'/pdfparser/pdf.php',x,{headers:this.headers}).subscribe(response=>
(console.log(this.contentitems=(response)))




    )
  
    
}



}

