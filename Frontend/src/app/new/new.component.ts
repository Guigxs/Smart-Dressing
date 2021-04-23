import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Category, RestService } from '../rest.service';

@Component({
  selector: 'app-new',
  templateUrl: './new.component.html',
  styleUrls: ['./new.component.scss']
})
export class NewComponent implements OnInit {

  categories: Category[] = []
  
  name

  constructor(private router: Router, public rest:RestService, ) { }

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
    this.router.navigate(["home", {"category":category}])
  }

  onClothSubmit(){
    console.log("sumb")
  }

  onCategorySubmit(){
    console.log("cate sub")
  }

}
