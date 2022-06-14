import { NgxPaginationModule } from 'ngx-pagination';
import { RouterModule } from '@angular/router';

import {  HttpClientModule } from '@angular/common/http';
import { NgModule, Component } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { BarRatingModule } from "ngx-bar-rating";
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { RatingComponent } from './rating/rating.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NavComponent } from './nav/nav.component';
import { LayoutModule } from '@angular/cdk/layout';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatButtonModule } from '@angular/material/button';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatIconModule } from '@angular/material/icon';
import { MatListModule } from '@angular/material/list';
import { CoursesComponent } from './courses/courses.component';
import { FormsModule } from '@angular/forms'; 
import { ReactiveFormsModule } from '@angular/forms';

import { HomeComponent } from './home/home.component';
import { SigninComponent } from './signin/signin.component';
import { SignupComponent } from './singup/singup.component';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { SidenavComponent } from './sidenav/sidenav.component';
import { ApiService } from './api.service';
import { RecomndedComponent } from './recomnded/recomnded.component';
import { ContentComponent } from './content/content.component';
import { CarouselModule } from 'ngx-bootstrap/carousel';


@NgModule({
  declarations: [
    AppComponent,
    RatingComponent,
    NavComponent,
    CoursesComponent,
    HomeComponent,
    SigninComponent,
  SignupComponent,
  
    SidenavComponent,
    RecomndedComponent,
    ContentComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    LayoutModule,
    MatToolbarModule,
    MatButtonModule,
    MatSidenavModule,
    MatIconModule,
    MatListModule,
    FormsModule,
    ReactiveFormsModule,
    BarRatingModule,
   HttpClientModule,
   RouterModule,
   NgbModule,
   NgxPaginationModule,
   CarouselModule.forRoot()
  

  ],
  providers: [HttpClientModule,ApiService,SigninComponent,NavComponent],
  bootstrap: [AppComponent]
})
export class AppModule {
 
  
  }
 
