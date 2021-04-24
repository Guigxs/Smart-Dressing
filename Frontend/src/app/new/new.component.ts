import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Cloth, Category, RestService } from '../rest.service';


@Component({
  selector: 'app-new',
  templateUrl: './new.component.html',
  styleUrls: ['./new.component.scss']
})
export class NewComponent implements OnInit {

  a = "hehehe"

  temperatures = [-10, -5, 0, 5, 10, 20, 30]
  weathers = ["sunny", "rainy", "foggy", "cloudy", "thunderstorm", "sonwy"]
  rainLevels = ["none", "drizzle", "medium", "heavy"]

  categories: Category[] = []
  
  newCloth = {
    name:null, 
    color:null,
    fabric:null,
    quantity:null,
    categories:[]
  }
  newCategory = {
    name:null, 
    temperature:null,
    weather:null,
    rainLevel:null
  }

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
    console.log(this.newCloth)
    this.rest.createCloth(this.newCloth).subscribe(resp => {
      this.router.navigate(["home"])
    })
  }

  onCategorySubmit(){
    this.newCategory.temperature = this.temperatures[this.newCategory.temperature]
    
    this.rest.createCategory(this.newCategory).subscribe(resp => {
      this.router.navigate(["home"])
    })
  }

}
