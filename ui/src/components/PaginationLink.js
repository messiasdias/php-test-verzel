export default function PaginationLink(props){
    const className = 
    props.page === props.meta.current_page && props.active ? 
    "page-link active" : "page-link"

    const selectPage = (e, page = 1) => {
        if (page > props.meta.last_page) page = props.meta.last_page
        if (page <= 0) page = 1
        if(props.onClick) props.onClick(e, page)
    }

    return (
        <li className="page-item">
            <a 
                className={className}
                onClick={(e) => selectPage(e, props.page)} 
                href="#"
                disabled={props.disabled}
            >{props.pageName}</a>
        </li>
    )
}