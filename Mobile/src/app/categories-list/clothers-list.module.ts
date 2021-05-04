import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CategoriesListComponent } from './categories-list.component';

@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule],
  declarations: [CategoriesListComponent],
  exports: [CategoriesListComponent]
})
export class CategoriesListComponentModule {}
