


## Cat and Dog Rest API Documentation

### Introduction

This project provides a REST API to fetch Cat and Dog breeds. This documentation covers the available routes, parameters, and some setup instructions.

### API Endpoints
Note: By default limit value is 10, [Cat and Dog Postmant Collection](https://documenter.getpostman.com/view/5578104/RWgqUxxh#c6f93ffe-4358-4969-a18e-e1e601791f09) attaching for reference

1.  **Fetch Breeds**:
    
    -   Endpoint: `/v1/breeds?page=1`
    -   Parameters:
        -   `page`: (optional) The page number. Default is `0`.
        -   `limit`: (optional) Number of breeds per page. Default is `10`.
2.  **Fetch Specific Breed**:
    
    -   Endpoint:  `/breed/{breed_id}`
    -   Parameters:
        -   `breed_id`: The id of the breed.
3.  **Fetch Images**:
    
    -   Endpoint: `/image/{image_id}`
    -   Parameters:
        -   `image_id`: The id of the image to get

_Note_: For pattern details of route parameters, refer to `App\Providers\RouteServiceProvider`.

### Docker Setup [link](https://www.docker.com/products/docker-desktop/)

#### Build and Run

To setup and run the Docker container, use the script:

bashCopy code

`sh docker.sh <branch> <command>` 

-   **1st parameter (`branch`)**:
    
    -   `develop` or `dev`: Uses the development environment.
    -   `staging` or `staging`: Uses the staging environment.
    -   `master`, `main`, or `prod`: Uses the production environment.
-   **2nd parameter (`command`)**:
    
    -   `up`: Start the container.
    -   `build`: Build the Docker container.
    -   `down`: Stop the container.
    -   `ps`: List containers.
    -   `exec`: Execute a command in a running container.

For example, to build the project for the production environment, you'd run:

bashCopy code

`sh docker.sh prod build` 

#### Cleanup

To remove unused Docker images or containers:

bashCopy code
`sh docker-clean.sh` 

### Miscellaneous
The API runs on port `8181`.
`http://localhost:8181`