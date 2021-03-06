import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { RestService } from '../rest.service';

@Component({
  selector: 'app-location',
  templateUrl: './location.component.html',
  styleUrls: ['./location.component.scss']
})
export class LocationComponent implements OnInit {

  constructor(private router: Router, public rest:RestService, private route: ActivatedRoute ) { }

  locations

  ngOnInit(): void {
    const category = this.route.snapshot.paramMap.get('category');

    this.getAllLocations()
  }

  getAllLocations(){
    this.rest.getAllLocations().subscribe((resp)=> {
      this.locations = resp
    })
  }

  updateLocation(location){
    this.rest.deleteLocation().subscribe(()=> {
      this.rest.createLocation(location.name, location.capital).subscribe((resp)=> {
        this.router.navigate([""])
      })
    })
  }

}
