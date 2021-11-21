import React from 'react'

export default props => {
    if (props.is) {
        return props.children
    } else {
        return false
    }
}
