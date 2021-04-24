import { Component, OnDestroy, OnInit } from '@angular/core';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';
import { Category, RestService } from '../rest.service';


@Component({
  selector: 'app-new',
  templateUrl: './new.component.html',
  styleUrls: ['./new.component.scss']
})
export class NewComponent implements OnInit, OnDestroy {

  temperatures = [-10, -5, 0, 5, 10, 20, 30]
  weathers = ["sunny", "rainy", "foggy", "cloudy", "thunderstorm", "snowy"]
  rainLevels = ["none", "drizzle", "medium", "heavy"]

  categories: Category[] = []
  
  newCloth;
  newCategory;

  navigationSubscription;
  constructor(private router: Router, public rest:RestService,  private route: ActivatedRoute) { 
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
      // If it is a NavigationEnd event re-initalise the component
      if (e instanceof NavigationEnd) {
        this.initialiseInvites();
      }
    });
  }

  ngOnDestroy(): void {
    if (this.navigationSubscription) {  
      this.navigationSubscription.unsubscribe();
   }
  }
  initialiseInvites() {
    this.ngOnInit()
  }

  ngOnInit(): void {
    this.newCategory = {
      name:null, 
      temperature:null,
      weather:null,
      rainLevel:null
    }
    this.newCloth = {
      name:null, 
      color:null,
      fabric:null,
      quantity:null,
      categories:[]
    }

    this.getCategories()
  }

  getCategories(){
    this.rest.getCategories().subscribe((resp) => {
      this.categories = resp
    })
  }

  deleteCategory(category){
    this.rest.deleteCategory(category).subscribe(resp => {
      this.router.navigate(["new"])
    })
  }

  selectCategory(category){
    this.router.navigate(["home", {"category":category}])
  }

  onClothSubmit(){
    this.rest.createCloth(this.newCloth).subscribe(resp => {
      this.router.navigate(["home"])
    })
  }

  onCategorySubmit(){
    this.newCategory.temperature = this.temperatures[this.newCategory.temperature]
    
    this.rest.createCategory(this.newCategory).subscribe(resp => {
      this.router.navigate(["new"])
    })
  }

}
