import React from 'react'
import { FormGroup, Label, Input, FormFeedback, FormText } from 'reactstrap'
import If from './If'
const FormItem = props => {
    return (
        <FormGroup>
            <Label for={props.name}>{props.label}</Label>
            <Input
                type={props.type ?? 'text'}
                name={props.name}
                id={props.name}
                value={props.value}
                onChange={props.action}
                invalid={props.invalid ?? ''}
                required={props.required ?? ''}
            />
            <If is={props.feedBack}>
                <FormFeedback>{props.feedBack}</FormFeedback>
            </If>
            <If is={props.required}>
                <FormText>{props.required ?? ''}</FormText>
            </If>
        </FormGroup>
    )
}

export default FormItem
