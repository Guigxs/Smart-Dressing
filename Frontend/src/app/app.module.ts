import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { CategoriesComponent } from './categories/categories.component';
import { HomeComponent } from './home/home.component';
import { NewComponent } from './new/new.component';
import { HeaderComponent } from './header/header.component';
import { LocationComponent } from './location/location.component';


@NgModule({
  declarations: [
    AppComponent,
    CategoriesComponent,
    HomeComponent,
    NewComponent,
    HeaderComponent,
    LocationComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
