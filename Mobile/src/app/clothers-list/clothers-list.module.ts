import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ClothersListComponent } from './clothers-list.component';

@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule],
  declarations: [ClothersListComponent],
  exports: [ClothersListComponent]
})
export class ClothersListComponentModule {}
