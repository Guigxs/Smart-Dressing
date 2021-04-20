import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { NewComponent } from './new/new.component'
import { HomeComponent } from './home/home.component'
import { LocationComponent } from './location/location.component'

const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'new', component: NewComponent },
  { path: 'location', component: LocationComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
