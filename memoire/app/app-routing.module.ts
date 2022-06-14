import { ContentComponent } from './content/content.component';
import { RecomndedComponent } from './recomnded/recomnded.component';
import { SignupComponent } from './singup/singup.component';
import { SigninComponent } from './signin/signin.component';
import { CoursesComponent } from './courses/courses.component';
import { HomeComponent } from './home/home.component';
import { NgModule, Component } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { RatingComponent } from './rating/rating.component';

const routes: Routes = [   {
path:'' ,component:HomeComponent},
{path:'courses', component:CoursesComponent} ,
{path:'signin', component:SigninComponent},
{path:'about', component:RatingComponent},
{path:'signup', component:SignupComponent},
{path:'recommended', component:RecomndedComponent},
{path:'content', component:ContentComponent},

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
