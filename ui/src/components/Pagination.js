import Link from './PaginationLink'

export default function Pagination(props){
    return (
        <nav aria-label="Page navigation example">
            <ul className="pagination">
                <Link 
                    meta={props.meta}
                    page={1} 
                    pageName={'Primeira'} 
                    onClick={props.onClick}
                />

                <Link 
                    meta={props.meta}
                    page={props.meta.current_page - 1} 
                    pageName={'Anterior'} 
                    onClick={props.onClick}
                />

                <Link 
                    meta={props.meta}
                    page={props.meta.current_page} 
                    pageName={props.meta.current_page} 
                    onClick={props.onClick}
                    active={true}
                />

                <Link 
                    meta={props.meta}
                    page={props.meta.current_page + 1} 
                    pageName={'Proxima'}
                    disbled={props.meta.last_page < (props.meta.current_page + 1)}
                    onClick={props.onClick}
                />

                <Link
                    meta={props.meta} 
                    page={props.meta.last_page} 
                    pageName={'Ultima'}
                    onClick={props.onClick}
                />
            </ul>
        </nav>
    )
}