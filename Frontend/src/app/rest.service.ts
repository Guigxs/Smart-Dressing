import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http'
import { Observable } from 'rxjs'

const endpoint = "http://localhost:8000/api"

export interface Category {
  id: number,
  name: string, 
  temperature: number,
  weather: string,
  rainLevel: string,
}

export interface Cloth {
  id: number,
  name: string, 
  color: string,
  fabric: string,
  quantity: string,
}

@Injectable({
  providedIn: 'root'
})
export class RestService {

  constructor(private http: HttpClient) { }

  getCategories(): Observable<any> {  
    return this.http.get<Category>(endpoint+"/category")
  }
  getClothersForToday(): Observable<any> {
    return this.http.get<Cloth>(endpoint+"/getCloth")
  }
  getClothers(category=null): Observable<any> {
    if (category){
      return this.http.get<Cloth>(endpoint+"/cloth/"+category)
    }
    return this.http.get<Cloth>(endpoint+"/cloth")
  }
}
