import React from 'react';
import { Card, Button, Spin } from 'antd';
import { NoData } from '../helpers'

export default class RobotsReport extends React.Component {

    state = {
        website: JSON.parse(this.props.website),
        loading: true,
        icon: '/favicon.png',
        title: '',
        description: '',
        url: '',
        image: '',
    };

    componentDidMount() {
        this.update();
    };

    update = async(refresh = false) => {
        await this.setState({
            loading: true
        });

        let endpoint = this.props.endpoint;

        if (refresh) {
           endpoint += '?refresh=1';
        }

        const response = (await window.axios.get(endpoint)).data;

        this.setState({
            ...response,
            loading: false,
        }, () => {
            if (this.state.icon) {
                document.getElementById('favicon').href = this.state.icon
            }
        });
    };

    forceUpdate = () => {
        return this.update(true);
    };

    renderReport = () => {
        const { title, description, image, url } = this.state;

        if (!title) {
            return <NoData />
        }

        return (
            <div className="flex flex-wrap items-center">
                <div className="w-1/2 pr-8">
                    { image ? <img className="w-full h-auto shadow-lg" src={ image } alt={ title } /> : <p>No image fround.</p>}
                </div>
                <div className="w-1/2">
                    <h2>{ title || 'No title found.' }</h2>
                    <p>{ description || 'No description found.' }</p>
                    { url ? <a href={ url } rel="noreferrer noopener" target="_blank"><small>{ url }</small></a> : <p>No URL found.</p>}
                </div>
            </div>
        )
    };

    renderBusy = () => {
        const { now, previous } = this.state;

        if (now && previous) {
            return this.renderReport()
        }

        return <Spin />
    };

    render() {
        const { loading } = this.state;

        return (
            <div>
                <Card
                    title="Open Graph"
                    extra={ <Button loading={ loading } onClick={ this.forceUpdate }>Refresh</Button> }
                >
                    { !loading ? this.renderReport() : this.renderBusy() }
                </Card>
            </div>
        )
    }
}
