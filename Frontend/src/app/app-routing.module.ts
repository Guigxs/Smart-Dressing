import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { NewComponent } from './new/new.component'
import { HomeComponent } from './home/home.component'
import { LocationComponent } from './location/location.component'

const routes: Routes = [
  { path: '', redirectTo: '/home', pathMatch: 'full', runGuardsAndResolvers: 'always',},
  { path: 'home', component: HomeComponent, runGuardsAndResolvers: 'always',},
  { path: 'new', component: NewComponent, runGuardsAndResolvers: 'always' },
  { path: 'location', component: LocationComponent, runGuardsAndResolvers: 'always' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {onSameUrlNavigation: 'reload'})],
  exports: [RouterModule]
})
export class AppRoutingModule { }
