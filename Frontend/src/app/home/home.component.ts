import { Component, OnDestroy, OnInit } from '@angular/core';
import { NavigationEnd, Router } from '@angular/router';
import { Cloth, RestService, Wardrobe } from '../rest.service';

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
  constructor(private router: Router, public rest:RestService, ) {
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
      // If it is a NavigationEnd event re-initalise the component
      if (e instanceof NavigationEnd) {
        this.initialiseInvites();
      }
    });
   }


  ngOnInit(): void {
    this.getWardrobe()
    this.getWeather()
    this.getClothers()
  }

  ngOnDestroy(): void {
    if (this.navigationSubscription) {  
      this.navigationSubscription.unsubscribe();
   }
  }

  initialiseInvites() {
    this.ngOnInit()
  }

  getClothers(){
    this.rest.getClothers().subscribe((resp) => {
      this.clothers = resp
    })
  }

  getWeather(){
    this.rest.getWeather().subscribe((resp) => {
      console.log(resp.name)
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

  removeWardrobe(id){
    this.rest.removeWardrobe(id).subscribe(()=>{
      this.ngOnInit()
    })
  }

  createWardrobe(){
    this.rest.createWardrobe().subscribe(()=> {
      this.ngOnInit()
    })
    
  }

}
