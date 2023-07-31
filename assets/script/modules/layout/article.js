import {buildTag } from '../helpers/buildtag.js';
import { fchRequest } from '../helpers/fetch.js';
const Layout = function() {
    let lo = this;
    let article;
    const _FIELDS = 'author,excerpt,title,link,featured_media,slug,date,nextpost,content,closest,breadcrumb';
    lo.init = async ({type}) => {
        let id = document.querySelector('meta#pageid').content;
        let post = await lo.getPost({id});
        article = lo.collect();
        article = lo.renderContent({article, post, first: true});
        let alla = article.content.querySelectorAll('a');
        console.log(alla);
    }

    lo.getPost = ({id}) => fchRequest({
        ftchURI: `/wp-json/wp/v2/posts/${id}?_fields=${_FIELDS}`,
        data: {
            method: 'GET',
        },
    })

    lo.collect = () => {
        let article = document.querySelector('article.article');
        return {
            breadcrumb: article.querySelector('div.breadcrumb'),
            title: article.querySelector('h1.title'),
            author: article.querySelector('span.author'),
            date: article.querySelector('span.date'),
            feature: article.querySelector('figure.feature'),
            content: article.querySelector('div.content'),
            closest: article.querySelector('div.closest'),
        }
    }
    lo.renderContent = ({ article, post, first}) => {
        let render = Object.entries(post).forEach(([method, data ]) => {
            if (lo[method]) lo[method]({article, data, first});
        })
        return article;
    }

    lo.author = ({article, data, first}) => {
        let author = buildTag({
            tag: 'a',
            href: data.link,
            innerHTML: `Author: ${data.display_name}`,
        });
        if (first === false) article.author.innerHTML = '';
        article.author.append(author);
    }

    lo.content = ({article, data, first}) => {   
        if (first === false) article.content.innerHTML = '';     
        article.content.innerHTML = data.rendered;
    }

    lo.breadcrumb = ({article, data, first}) => {
        let home = buildTag({
            tag: 'a',
            className: 'breadcrumb__item',
            href: '/',
            innerHTML: 'home',
        });
        let items = data.map(dat => buildTag({
            tag: 'a',
            className: 'breadcrumb__item',
            href: dat.link,
            innerHTML: dat.name, 
        }))
        if (first === false) article.breadcrumb.innerHTML = '';
        article.breadcrumb.append(home, ...items);
    }

    lo.featured_media = ({article, data, first}) => {
        let media = buildTag({
            tag: 'img',
            className: 'feature__img',
            src: data.src.large,
            srcset: data.srcset,
            loading: 'lazy',
            decoding: 'async',
        });
        if (first === false) article.feature.innerHTML = '';
        article.feature.append(media);
    }

    lo.title = ({article, data, first}) => {    
        if (first === false) article.title.innerHTML = '';
        article.title.innerHTML = data.rendered;
    }

    lo.date = ({article, data, first}) => {
        let [date] = data.split('T');
        if (first === false) article.date.innerHTML = '';
        article.date.innerHTML = `date: ${date}`;
    }

    lo.closest = ({article, data, first}) => {
        let closest = Object.entries(data)
            .map(([item, {title, ID, link}]) => 
                buildTag({
                    tag: 'a',
                    className: item,
                    href: link,
                    innerHTML: title,
                    postId: ID, 
                    onclick: event => lo.clientSideRendering({event}),  
                })
            );
        if (first === false) article.closest.innerHTML = '';
        article.closest.append(...closest);
    }

    lo.clientSideRendering = async ({event}) => {
        event.preventDefault();
        const id =  event.target.postId;
        window.history.pushState({}, '', event.target.href);
        let post = await lo.getPost({id});
        article = lo.renderContent({article, post, first: false});
    }
    
}

export default Layout ;

//http://hackintosh/wp-json/wp/v2/posts/3737?_fields=author,id,excerpt,title,link,categories,featured_media,slug,date,nextpost 
