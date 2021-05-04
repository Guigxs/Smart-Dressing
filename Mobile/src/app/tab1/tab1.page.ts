import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';

import { Cloth, RestService, Category } from '../rest.service';

@Component({
  selector: 'app-tab1',
  templateUrl: 'tab1.page.html',
  styleUrls: ['tab1.page.scss']
})
export class Tab1Page implements OnInit, OnDestroy{

  
  loading = true
  clothers: Cloth[] = []
  categories: Category[] = []
  add = "false"

  newCloth;

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
    this.newCloth = {
      name:null, 
      color:null,
      fabric:null,
      quantity:null,
      categories:[]
    }
    this.fetchClothers()
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

  addCloth(){
    if (this.add == "true") this.add = "false"
    else this.add = "true"
  }

  fetchClothers(){
    this.rest.getClothers(null).subscribe((resp) => {
      this.clothers = resp
      this.loading = false
    })
  }

  fetchCategories(){
    this.rest.getCategories().subscribe((resp) => {
      this.categories = resp
      this.loading = false
    })
  }

  onClothSubmit(){
    this.rest.createCloth(this.newCloth).subscribe(resp => {
      this.router.navigate(["tabs/tab1", {"add":"false"}])
    })
  }
  
}
