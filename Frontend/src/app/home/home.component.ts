import { Component, OnDestroy, OnInit } from '@angular/core';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';
import { Cloth, RestService } from '../rest.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit, OnDestroy{

  clothers: Cloth[] = []
  weather = {
    name:undefined,
    main:{
      feels_like:undefined
    }
  }
  wardrobe = undefined
  navigationSubscription;
  constructor(private router: Router, public rest:RestService, private route: ActivatedRoute) {
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
      // If it is a NavigationEnd event re-initalise the component
      if (e instanceof NavigationEnd) {
        this.initialiseInvites();
      }
    });
   }


  ngOnInit(): void {
    const category = this.route.snapshot.paramMap.get('category');
    const searchText = this.route.snapshot.paramMap.get('search');
    
    this.getWardrobe()
    this.getWeather()

    if (!searchText){
      this.getClothers(category)
    }
    else {
      this.searchCloth(searchText)
    }
  }

  ngOnDestroy(): void {
    if (this.navigationSubscription) {  
      this.navigationSubscription.unsubscribe();
   }
  }

  initialiseInvites() {
    this.ngOnInit()
  }

  getClothers(category){
    this.rest.getClothers(category).subscribe((resp) => {
      this.clothers = resp
    })
  }

  getMyCloth(){
    this.rest.getClothersForToday().subscribe((resp) => {
      this.clothers = resp
    })
  }

  searchCloth(text){
    this.rest.search(text).subscribe(resp => {
      this.clothers = resp
    })
  }

  deleteCloth(cloth){
    this.rest.deleteCloth(cloth).subscribe(resp=> {
      this.router.navigate(["home"])
    })
  }

  getWeather(){
    this.rest.getWeather().subscribe((resp) => {
      this.weather = resp
    })
  }

  getWardrobe(){
    this.rest.getWardrobe().subscribe((resp) => {
      if (resp.length == 0){
        this.wardrobe = null
      }
      else{
        this.wardrobe = resp
      }
    })
  }

  deleteWardrobe(id){
    this.rest.deleteWardrobe(id).subscribe(()=>{
      this.ngOnInit()
    })
  }

  createWardrobe(){
    this.rest.createWardrobe().subscribe(()=> {
      this.ngOnInit()
    }) 
  }
}
