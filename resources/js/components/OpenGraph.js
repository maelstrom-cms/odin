import React from 'react';
import { Card, Button, Spin, Alert } from 'antd';
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
        image_width: null,
        image_height: null,
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

    analyiseAssets = ({ target }) => {
        this.setState({
            image_width: target.naturalWidth,
            image_height: target.naturalHeight,
        })
    };

    renderReport = () => {
        const { title, image } = this.state;

        if (!title) {
            return <NoData />
        }

        return (
            <div className="flex flex-wrap items-center">
                <div className="w-1/2 pr-8">
                    { image ? this.renderImage() : <p>No image fround.</p> }
                </div>
                <div className="w-1/2">
                    { this.renderTexts() }
                </div>
            </div>
        )
    };

    renderImage = () => {
        const { title, image, image_height, image_width } = this.state;

        const warning = function() {
            if (image_height !== 630 || image_width !== 1200) {
                return <Alert className="mt-4" showIcon={ true } type="warning" message={`Image dimensions should be: 1200x630 - But provided image was ${image_width}x${image_height}`} />
            }
        };

        return <>
            <img className="max-w-full h-auto shadow-lg" onLoad={ this.analyiseAssets } src={ image } alt={ title } />
            { !!image_height && warning() }
        </>
    };

    renderTexts = () => {
        const { title, description, url } = this.state;

        const warning = function() {
            const messages = [];

            if (title.length < 50 || title.length > 60) {
                messages.push(`The page title is ${title.length} characters long - However the recommended length is between 50 and 60 characters.`);
            }

            if (description.length < 100 || description.length > 160) {
                messages.push(`The page description is ${description.length} characters long - However the recommended length is between 100 and 160 characters.`);
            }

            if (!messages.length) {
                return null;
            }

            return <Alert className="mt-4" showIcon={ true } type="warning" message={
                messages.map((message, k) => <p className={k ? 'mt-2 mb-0' : 'my-0'} key={ k }>{ message }</p>)
            } />
        };

        return (
            <>
                <h2>{ title || 'No title found.' }</h2>
                <p>{ description || 'No description found.' }</p>
                { url ? <a href={ url } rel="noreferrer noopener" target="_blank"><small>{ url }</small></a> : <p>No URL found.</p>}
                { warning() }
            </>
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
