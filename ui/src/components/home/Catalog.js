import Axios from 'axios';
import { useState, useEffect } from 'react';

import CarImage from './../../assets/images/car-card.png'
import CatalogPagination from './../Pagination'
import CatalogSearch from './CatalogSearch'

function Catalog(){
    const [search, setSearch] = useState([])
    const [vehicles, setVehicles] = useState([])
    const [meta, setMeta] = useState({
        current_page: 1,
        last_page: 1
    })
    
    const getCatalog = (e = null, page = null) => {
        e?.preventDefault()

        let url = `${process.env.REACT_APP_API}/vehicles`
        if (search?.length) url += `/search?search=${search}&`
        else url += '?'
        url += `page=${page || meta.current_page}`

        Axios.get(url)
        .then(({data}) => {
            setVehicles(data.data)
            setMeta({
                current_page: data.current_page,
                last_page: data.last_page
            })
        })
        .catch((error) => {
            setVehicles([])
            setMeta({
                current_page: 1,
                last_page: 1
            })
        })
    }

    useEffect(() => getCatalog, []);

    return (
        <section className="catalog" id="catalog">
            <div className="content">
                <div className="title-wrapper-catalog">
                    <p>Encontre o que você procura</p>
                    <h3>Catálogo</h3>
                </div>

                <CatalogSearch 
                    meta={meta} 
                    search={search}
                    setSearch={setSearch}
                    setMeta={setMeta} 
                    setVehicles={setVehicles}
                    getCatalog={getCatalog} 
                />

                <CatalogPagination 
                    meta={meta} 
                    onClick={getCatalog} 
                />

                <div className="card-wrapper">
                    {vehicles.map((vehicle, i) => (
                        <div className="card-item" key={i}>
                            <img src={vehicle.image || CarImage} alt="Car" />
                            <div className="card-content">
                                <h3>{vehicle.name}</h3>
                                <p>{vehicle.description}</p>
                                <button type="button">Eu Quero!</button>
                            </div>
                        </div>
                    ))}
                </div>

                <CatalogPagination 
                    meta={meta} 
                    onClick={getCatalog} 
                />
            </div>
        </section>
    )
}

export default Catalog