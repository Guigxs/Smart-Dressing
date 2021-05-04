import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';

import { RestService, Category } from '../rest.service';

@Component({
  selector: 'app-tab2',
  templateUrl: 'tab2.page.html',
  styleUrls: ['tab2.page.scss']
})
export class Tab2Page implements OnInit, OnDestroy {

  temperatures = [-10, -5, 0, 5, 10, 20, 30]
  weathers = ["sunny", "rainy", "foggy", "cloudy", "thunderstorm", "snowy"]
  rainLevels = ["none", "drizzle", "medium", "heavy"]

  loading = true
  categories: Category[] = []
  add = "false"
  newCategory;

  navigationSubscription
  constructor(private router: Router, public rest:RestService, public route:ActivatedRoute) {
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
      // If it is a NavigationEnd event re-initalise the component
      if (e instanceof NavigationEnd) {
        this.initialiseInvites();
      }
    });
  }

  ngOnInit(): void {
    console.log("fetch")
    this.add = this.route.snapshot.paramMap.get('add');
    console.log(this.add)
    this.newCategory = {
      name:null, 
      color:null,
      fabric:null,
      quantity:null,
      categories:[]
    }
    this.fetchCategories()
  }

  ngOnDestroy() {
    if (this.navigationSubscription) {  
      this.navigationSubscription.unsubscribe();
    } 
  }

  initialiseInvites(){
    this.ngOnInit()
  }

  addCategory(){
    if (this.add == "true") this.add = "false"
    else this.add = "true"
  }

  fetchCategories(){
    this.rest.getCategories().subscribe((resp) => {
      this.categories = resp
      this.loading = false
    })
  }

  onCategorySubmit(){
    this.newCategory.temperature = this.temperatures[this.newCategory.temperature]
    
    this.rest.createCategory(this.newCategory).subscribe(resp => {
      this.router.navigate(["tabs/tab2", {"add":"false"}])
    })
  }
}
