import { Component, OnInit } from '@angular/core';
import { Category, RestService } from '../rest.service';

@Component({
  selector: 'app-new',
  templateUrl: './new.component.html',
  styleUrls: ['./new.component.scss']
})
export class NewComponent implements OnInit {

  categories: Category[] = []

  constructor(public rest:RestService, ) { }

  ngOnInit(): void {
    this.getCategories()
  }

  getCategories(){
    this.rest.getCategories().subscribe((resp) => {
      this.categories = resp
    })
  }

  removeCategory(category){
    console.log("remove"+category)
  }

  selectCategory(category){
    console.log("select"+category)
  }

}
