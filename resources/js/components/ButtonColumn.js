import React from 'react';
import { Button } from 'antd'

export default class ButtonColumn extends React.Component {
    render() {
        return <Button href={ this.props.text }>Edit</Button>
    }
}
