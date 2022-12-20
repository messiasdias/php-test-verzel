export default function Modal(props) {

    const ModalText = () => {
        if (props.text) return (<p>{props.text}</p>)
        if (props.content) return props.content
    }

    const ModalActions = () => {
        if (!props.actions) return (
            <>
                <button 
                    type="button" 
                    className="btn btn-secondary" 
                    data-bs-dismiss="modal"
                    onClick={props.handlerClose}
                >Fechar</button>
                <button 
                    type="button" 
                    data-bs-dismiss="modal"
                    className="btn btn-primary"
                    onClick={props.handlerSubmit}
                >Ok</button>
            </>
        )
        return props.actions
    }

    return (
        <div className="modal" id={props.id}>
            <div className="modal-dialog">
                <div className="modal-content">
                <div className="modal-header">
                    <h5 className="modal-title">{props.title}</h5>
                    <button 
                        type="button" 
                        className="btn-close" 
                        data-bs-dismiss="modal" 
                        aria-label="Close"  
                        id={`${props.id}Close`}
                        onClick={props.handlerSubmit}
                    ></button>
                </div>
                <div className="modal-body">
                    <ModalText />
                </div>
                <div className="modal-footer">
                    <ModalActions />
                </div>
                </div>
            </div>
        </div>
    )
}