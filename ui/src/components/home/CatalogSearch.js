import Axios from 'axios';

export default function CatalogSearch(props){
    const searchCatalog = (e, page = null) => {
        e?.preventDefault()

        // if(props.search) {
        //     Axios.get(`${process.env.REACT_APP_API}/vehicles/search?search=${props.search}&page=${page ||props.meta.current_page}`)
        //     .then(({data}) => {
        //         props.setVehicles(data.data)
        //         props.setMeta({
        //             current_page: 1,
        //             last_page: 1
        //         })
        //     })
        //     .catch(() => {
        //         props.setVehicles([])
        //         props.setMeta({
        //             current_page: 1,
        //             last_page: 1
        //         })
        //     })
        // }   
        // props.getCatalog(e)
    }

    return (
        <div className="filter-card">
            <input
                type="text"
                className="search-input"
                placeholder="Escolha seu modelo favorito"
                value={props.search}
                onChange={(e) => {
                    props.setSearch(e.target.value)
                    props.setMeta({
                        current_page: 1,
                        last_page: 1
                    })
                }}
                onKeyPress={(e) => {
                    if(e.key === 'Enter') props.getCatalog(e)
                }}
            />
            <button onClick={props.getCatalog} className="search-button">Buscar</button>
        </div>
    )
}