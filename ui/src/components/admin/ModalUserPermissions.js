export default function ModalUserPermissions(props) {
    console.log(props.user)
    return (
         <>
            {props.permissions.map((permission, i) => (
                <p key={i}>{permission.name}</p>
            ))}
         </>
     )
}