import { Component, OnInit } from '@angular/core';
import { RestService , Category } from '../rest.service'
import { Router } from '@angular/router'
import { HttpClient } from '@angular/common/http'

@Component({
  selector: 'app-categories',
  templateUrl: './categories.component.html',
  styleUrls: ['./categories.component.scss']
})
export class CategoriesComponent implements OnInit {

  categories: Category[] = []

  name = "coucou"
  constructor(public rest:RestService, ) { }

  ngOnInit(): void {
    this.getProducts()
  }

  getProducts(){
    this.rest.getCategories().subscribe((resp) => {
      this.categories = resp
    })
  }

}
